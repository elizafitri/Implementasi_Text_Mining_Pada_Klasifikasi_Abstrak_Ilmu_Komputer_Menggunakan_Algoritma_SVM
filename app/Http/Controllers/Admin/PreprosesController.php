<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use DB;
use App\df;
use App\hasil_token;
use App\Http\Controllers\Controller;
use App\idf;
use App\Keilmuan;
use App\Klasifikasi;
use App\Stopword;
use App\table_vector;
use App\tf;
use App\tfidf;
use App\Token;
use App\vector;
use markfullmer\porter2\Porter2;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Input;
// use Phpml\FeatureExtraction\TfIdfTransformer;
use Phpml\FeatureExtraction\TokenCountVectorizer;
use Phpml\Tokenization\WhitespaceTokenizer;

// define('WORD_BOUNDARY', "[^a-zA-Z']+");

class PreprosesController extends Controller
{
     /**
     * Create a new controller instance.
     *
     * @return void
     */

    //method ini agar halaman ini harus login dulu
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */

    //method untuk menampilkan daftar jurnal dalam Home/index
    public function jurnal()
    {
        //mengambil data jurnal dari model Klasifikasi.php berdasarkan id
        $jurnal = Klasifikasi::orderBy('id', 'asc')->get();

        //menampilkan data jurnal yang dipanggil diatas untuk ditampilkan di index
        return view('admin.index')->with([
             'jurnal' => $jurnal,
         ]);
    }

    //method untuk menampilkan form tambah data jurnal
    public function addForm()
    {
        //mengambil data kelas_keilmuan dari model Keilmuan.php berdasarkan id
        $keilmuan = Keilmuan::orderBy('id', 'desc')->get();

        //menampilkan data kelas_keilmuan yang dipanggil diatas untuk ditampilkan di form tambah data
        return view('admin.add')->with([
            'keilmuan' => $keilmuan
        ]);
    }

    //method untuk memproses penambahan data jurnal (di form action)
    public function addProses(Request $p)
    {
        //untuk memvalidasi bahwa data yang diisi nanti (dari 5 variabel dibawah) tidak null
        $this->validate($p, [
            'id_keilmuan' => 'required',
            'judul_jurnal' => 'required',
            'nama_penulis' => 'required',
            'abstrak' => 'required',
            'kata_kunci' => 'required'
        ]);
        
        //prosesnya disini
        $jurnal = Klasifikasi::create([
            'id_keilmuan' => request()->get('id_keilmuan'),
            'judul_jurnal' => request()->get('judul_jurnal'),
            'nama_penulis' => request()->get('nama_penulis'),
            'abstrak' => request()->get('abstrak'),
            'kata_kunci' => request()->get('kata_kunci')
          ]);
          $jurnal->save();
          return redirect()->route('jurnal');
    }

    //method untuk melakukan edit data jurnal
    public function editForm($id)
    {
        //mengambil data id jurnal dan id_keilmuan dari model Klasifikasi.php dan Keilmuan.php 
        $jurnal = Klasifikasi::where('id', $id)->first();
        $keilmuan = Keilmuan::orderBy('id', 'desc')->get();

        //menampilkan data yang dipanggil diatas untuk ditampilkan di form edit data
        return view('admin.edit')->with([
            'jurnal' => $jurnal,
            'keilmuan' => $keilmuan
          ]);
    }

    //method untuk memproses edit data jurnal (di form action)
    public function update(Request $r, $id)
    {
        //untuk memvalidasi bahwa data yang diisi nanti (dari 6 variabel dibawah) tidak null
        $this->validate($r, [
            'id_keilmuan' => 'required',
            'judul_jurnal' => 'required',
            'nama_penulis' => 'required',
            'abstrak' => 'required',
            'kata_kunci' => 'required'
        ]);

         //prosesnya disini
        $jurnal= Klasifikasi::find($id);
        $jurnal->id_keilmuan = $r->id_keilmuan;
        $jurnal->judul_jurnal = $r->judul_jurnal;
        $jurnal->nama_penulis = $r->nama_penulis;
        $jurnal->abstrak = $r->abstrak;
        $jurnal->kata_kunci = $r->kata_kunci;
        // dd($jurnal);
        $jurnal->save();
        return redirect()->route('jurnal');
    }

     //method untuk menghapus data jurnal berdasarkan id
    public function delete($id)
    {
        $jurnal = Klasifikasi::find($id);
        $jurnal->delete();
        return redirect()->route('jurnal');
    }

    //method untuk melakukan preproses jurnal
    public function preproses($id)
    {
        $jurnal = Klasifikasi::where('id', $id)->first();
        $kata = $jurnal->abstrak;
        $abstrak = $this->dokumen($kata);
        $low = strtolower($kata); //casefolding 
        $stoplist = print_r($abstrak, TRUE);
        // $stem = $this->porterstemmer_process($low);
        // $input = $this->porterstemmer_process($token);
        $token = array_unique(explode(" ",$abstrak)); 
        $hasil_token = print_r($token, TRUE); 
        $hitung_hasil_token = count($token); 
        $stem = $this->porterstemmer_process($hasil_token);

        $input = $token;

        // menampilkan fungsi yang dipanggil diatas untuk ditampilkan di form preproses
        return view('admin.preproses')->with([
            'jurnal' => $jurnal,
            'low' => $low,
            'hapus_stopword' => $stoplist,
            'token' => $hasil_token,
            'hasil_token' => $hitung_hasil_token,
            'stem' => $stem,
            'input' => $input
        ]);
    }

    public function createtoken(Request $r)
    {
       //untuk memvalidasi agar tidak ada tabel yang kosong
        $this->validate($r, [
            'token' => 'required'
        ]);
            
        $token = $r->token;
        $token_array = [];

        //supaya keseluruhan data di array bisa di token_array
        foreach ($token as $key) {
            $token_array[] = [       
            'token' => $key,            
            ];
        }  
              
        for ($i=0; $i < count($token_array); $i++) { 
            $cek = DB::table('new_tokens')->select('token')->where('token', $token_array[$i])->count();
            if ($cek < 1) {
                //untuk mengecek jika kata hasil token belum ditampung 
                Token::insert($token_array[$i]);
            }
        }          
        
        return back()->withInput();
    }

    //method untuk menampilkan hasil token (sudah di preproses) yang sudah terurut
    public function viewTokenUrut()
    {
        $token = Token::all();
        $sorted = $token->sortBy('token');
        $sorted->values()->all();

        return view('token_urut')->with([
            'sorted' => $sorted
        ]);
        
    }
    
    //method melakukan proses simpan token hasil preproses
    public function saveTokenUrut(Request $request)
    {
        $this->validate($request, [
            'token_urut' => 'required'
        ]);

        $token_urut = $request->token_urut;
        $input_array = [];
        foreach ($token_urut as $key => $value) {
            $input_array[] = [
                'token' => $value,
            ];
        }

        for ($i=0; $i < count($input_array); $i++) { 
            $cek = DB::table('tokens')->select('token')->where('token', $input_array[$i])->count();
            if ($cek < 1) {
                //untuk mengecek jika kata hasil token belum ditampung 
                Token::insert($input_array[$i]);
            }
        } 
        // Hasil_token::insert($input_array);
        return back()->withInput();
    }

    //method untuk menampilkan array dokumen (berdasarkan id_doc) tempat token ditemukan
    public function arrayvector()
    {
        // $jajal = Token::all();
        $jajal = Hasil_token::all();
        $jurnal = Klasifikasi::all();
        // $jurnal = Klasifikasi::find(1);
        // $abstrak = $jurnal->preproses;
        // // $pre = $this->preproses($abstrak);
        // $token = explode(" ", $abstrak);
    
        return view('admin.vector')->with([
            // 'cinta' => $cinta,
            'jajal' =>$jajal,
            'jurnal' => $jurnal,
            'bobot' => $bobot
            // 'token' => $token,
            // 'array' => $array,
            // 'controller' => $this
        ]);
    }
    

    public function savearray(Request $request)
    {
        $input = Input::all();
        $vector_array = [];
        $id_term = $input['id_term'];
        foreach ($id_term as $key => $id_term) {
            $vector_array[] = [
                'id_term' => $input['id_term'][$key],
                'vector_doc' => trim(preg_replace('/\s\s+/', ' ', str_replace("\n\r\t", " ", $input['vector_doc'][$key]))),
                // 'total_vector' => array_count_values($input['adji'][$key])
            ];
        } 
        // dd($vector_array);
        // return var_dump($token_array);
        Table_vector::insert($vector_array);
        return back()->withInput();
    }

    //method untuk melakukan preproses jurnal
    public function hasilPre(Request $r, $id)
    {
        $this->validate($r, [
            'preproses' => 'required'
            
        ]);
        
        //$id = $r->id;
        $jurnal= Klasifikasi::find($id);
        $jurnal->preproses = $r->preproses;
        $jurnal->save();
        return redirect()->route('jurnal');
    }


    public function editPre($id)
    {
        $jurnal = Klasifikasi::where('id', $id)->first();
        $kata = $jurnal->abstrak;
        $abstrak = $this->preproses($kata);

        return view('admin.edit_preproses')->with([
            'abstrak' => $abstrak,
            'jurnal' => $jurnal
        ]);
        
    }

    public function vectorPre($id)
    {
        $jurnal = Klasifikasi::where('id', $id)->first();
        $kata = $jurnal->abstrak;
        // $abstrak = $this->preproses($kata);

        return view('admin.vectordoc')->with([
            'jurnal' => $jurnal
        ]);
    }

    public function tampilPre()
    {
        $token = DB::table('klasifikasis')->select('preproses')->get();
        return $jurnal = Klasifikasi::all();


    }

    public function dokumen($teks)
    {
        // //ubah ke huruf kecil
        $teks = strtolower($teks);

        //bersihkan tanda baca, ganti dengan space 
        $teks = str_replace(".", " ", $teks);
        $teks = str_replace(",", " ", $teks);           
        $teks = str_replace("/", " ", $teks);
        $teks = str_replace("}", " ", $teks);           
        $teks = str_replace("{", " ", $teks); 
        $teks = str_replace("]", " ", $teks);
        $teks = str_replace("[", " ", $teks); 
        $teks = str_replace(":", " ", $teks);
        $teks = str_replace("=", " ", $teks); 
        $teks = str_replace('"', " ", $teks); 
        $teks = str_replace("+", " ", $teks);
        $teks = str_replace("-", " ", $teks);           
        $teks = str_replace("?", " ", $teks); 
        $teks = str_replace("!", " ", $teks);
        $teks = str_replace("$", " ", $teks);
        $teks = str_replace("(", " ", $teks);
        $teks = str_replace(")", " ", $teks);
        $teks = trim($teks);

        //mengecek abstrak yang mengandung stoplist dari database 'stopwords'
        $astoplist = Stopword::all();
        
            foreach ($astoplist as $word) {
                //cara cek nya yaitu tiap kalimat di abstrak di potong per-string (pake fungsi rtrim)
                $word = rtrim($word->stoplist);
                //kalo udah di trim, kata stoplist yang terbaca diganti dengan white space
                $teks = preg_replace("/\b$word\b/i", " ", $teks);
            }

            $teks = trim(preg_replace('/[^a-zA-Z0-9 -]/', '', $teks));
            $teks = trim(preg_replace('/[0-9]/', '', $teks));
            $teks = trim(preg_replace('/\s\s+/', ' ', str_replace("\n", " ", $teks)));
            // <([0-9a-f]{4})>\s+<([0-9a-f]{4})>\s+<([0-9a-f]{4})>
            // $teks = trim(preg_replace('//', '', $teks));
                // $teks = preg_replace('/[^a-zA-Z0-9 -]/', '', $teks);
                // $teks = trim(preg_replace('/\b(\w+)\s+\1\b/', " ", $teks));

                // foreach ($teks as $jajan) {
                //     $teks = preg_replace('/(\w{2,})(?=.*?\\1)\W*/', '', $jajan);
                // }

                
            $teks = $this->porterstemmer_process($teks);
            return $teks;
    }
        
        //fungsi untuk menampilkan hasil pencarian
        // public function coba()
        // {
        //     return DB::table('tokens')->select('token')->get();
        // }

        //fungsi ini untuk menyimpan token hasil preproses ke database 'tokens'
   

        //fungsi untuk mencari index kata yang dicari dari abstrak (berdasarkan id) hasil tokenisasi
        // public function result_token()
        // {
        //     $token = DB::table('tokens')->select('token')->get();
        //     $jurnal = Klasifikasi::find(1)->first();

        //     $teks = $jurnal->abstrak;
        //     $pre = $this->preproses($teks);
        //     $token = explode(" ", $pre);

        //     echo '<pre>' . print_r($token) . '<pre>';
        //     $array = [];
        //     foreach ($token as $key => $value) {
        //         $value;
        //     }

        //     //cara pertama
        //     $cari = 'system';
        //     echo "Kata yang dicari <b>" . $cari . "</b> ada pada index : <br>";
        //     $keys = array_keys($token, $cari);
        //     echo '<pre> <b>' . print_r($keys, true) . '<b> <pre>';

        //     // // cara kedua
        //     // echo "D".$jurnal->id;
            
        //     // $hitung = count($token);
        //     // $kata = 'system';
        //     // $d = [];
        //     // for ($i=0; $i < $hitung; $i++) { 
        //     //     if($token[$i] == $kata){
        //     //         echo $i." ";
        //     //     }
        //     // }            
        // }

        public function cobasnow()
        {
            $text = Porter2::stem('change');
            echo $text.'<br>';

            $text = Porter2::stem('consisting');
            echo $text.'<br>';

            $text = Porter2::stem('consistency');
            echo $text;
        }

    public function porterstemmer_process($text) {
        // Split into words.
        $words = preg_split('/(' . WORD_BOUNDARY . '+)/', $text, -1, PREG_SPLIT_DELIM_CAPTURE);
        if (!count($words)) {
              return $text;
        }
        
        // Process each word, skipping delimiters.
        $isword = !preg_match('/' . WORD_BOUNDARY . '/', $words[0]);
        foreach ($words as $k => $word) {
            if ($isword) {
                $words[$k] = Porter2::stem($word);
            }  
            $isword = !$isword;
        }
        return implode('', $words);
    }

    public function saveBobot(Request $r)
    {

        $input = Input::all();
        $tf_array = [];
        $id_doc = $input['id_doc'];
        foreach ($id_doc as $key => $id_doc) {
            $tf_array[] = [
                'id_term' => $input['id_term'][$key],
                'id_doc' => $input['id_doc'][$key],
                'indeks' => $input['indeks'][$key],
                'tf' => $input['tf'][$key]
            ];
        }
    
        for ($i=0; $i < count($tf_array); $i++) { 
            $cek = DB::table('tf')->where([
                ['id_term', '=', $tf_array[$i]],
                ['id_doc', '=', $tf_array[$i]]
            ])->count();
            if ($cek < 1) {
                //untuk mengecek jika kata hasil token belum ditampung 
                tf::insert($tf_array[$i]);
            }
        } 
        // dd($tf_array);
        // tf::insert($tf_array);
        return back()->withInput();
    }

    public function bobot($id)
    {
        $token = Token::all();
        $abstrak = Klasifikasi::find($id);
        $preproses = $abstrak->preproses;
        $tokenisasi = explode(" ", $preproses);
        //idf(term) = log(D/df) dimana df adl jumlah dokumen yang mengandung suatu term
        // tfidf = tf x idf
        return view('admin.vectordoc')->with([
            'abstrak' => $abstrak,
            'tokenisasi' => $tokenisasi,
            'token' => $token
        ]);
        // $token = Token::all();
        // $abstrak = Klasifikasi::find($id);
        // $preproses = $abstrak->preproses;
        // $tokenn = explode(" ", $preproses);
        // $tokenisasi = array_unique(explode(" ",$preproses));
        // $hitung_token = print_r($tokenn, TRUE); 
        // $jumlah_term = count($tokenn);

        // //idf(term) = log(D/df) dimana df adl jumlah dokumen yang mengandung suatu term
        // // tfidf = tf x idf

        // return view('admin.vectordoc')->with([
        //     'abstrak' => $abstrak,
        //     'token' => $token,
        //     'tokenn' => $tokenn,
        //     'tokenisasi' => $tokenisasi,
        //     'hitung_token' => $hitung_token,
        //     'jumlah_term' => $jumlah_term
        // ]);

    }

    public function viewDF()
    {
        $term = tf::all();
        return view ('admin.df')->with([
            'term' => $term
        ]);

        // $break = 0;
        // foreach ($term as $key => $value) {
        //     // if($break == 100) break;{
        //         $tf = $value->id;
        //         $df = tf::where('id_term', $tf)->get();

        //         echo $df->count().'------'.$tf.'<br>';
        //     // }
        //     // $break++;
        //     # code...
        // }
        // # code...
    }

    public function saveDf(Request $r)
    {
        $input = Input::all();
        $df_array = [];
        $id_term = $input['id_term'];
        foreach ($id_term as $key => $id_term) {
            $df_array[] = [
                'id_term' => $input['id_term'][$key],
                'df' => $input['df'][$key],
            ];
        }

        for ($i=0; $i < count($df_array); $i++) { 
            $cek = DB::table('df')->where('id_term', $df_array[$i])->count();
            if ($cek < 1) {
                //untuk mengecek jika kata hasil token belum ditampung 
                df::insert($df_array[$i]);
                // dd($df_array[$i]);
            }
        } 
    
        return back()->withInput();
    }

    //idf(term) = log(D/df) dimana df adl jumlah dokumen yang mengandung suatu term
    // tfidf = tf x idf

    public function viewIdf()
    {
        $doc = 144;
        $df = df::all();

        return view ('admin.idf')->with([
            'doc' => $doc,
            'df' => $df
        ]);

        // $break = 0;
        // foreach ($df as $key => $value) {
        //     if($break == 1891) break;{
        //         $df = $value->df;
        //         $idf = log($d/$df);
        //         if($idf != 0) {
        //             echo round($idf, 4).'<br>';
        //         }
        //     }
        //     $break++;
        // }
    }

    public function saveIDF(Request $r)
    {
        $input = Input::all();
        $idf_array = [];
        $id_term = $input['id_term'];
        foreach ($id_term as $key => $id_term) {
            $idf_array[] = [
                'id_term' => $input['id_term'][$key],
                'idf' => $input['idf'][$key],
            ];
        }

        for ($i=0; $i < count($idf_array); $i++) { 
            $cek = DB::table('idf')->where('id_term', $idf_array[$i])->count();
            if ($cek < 1) {
                //untuk mengecek jika kata hasil token belum ditampung 
                idf::insert($idf_array[$i]);
            }
        }  
        // dd($df_array);
        // tf::insert($tf_array);
        return back()->withInput();
    }

    public function viewTfidf()
    {
        $tf = tf::all();
        $idf = idf::all();

        return view ('admin.tfidf')->with([
            'tf' => $tf,
            'idf' => $idf
        ]);


    }

    public function saveTfidf(Request $r)
    {
        $input = Input::all();
        $tfidf_array = [];
        $id_term = $input['id_term'];
        foreach ($id_term as $key => $id_term) {
            $tfidf_array[] = [
                'id_term' => $input['id_term'][$key],
                'tfidf' => $input['tfidf'][$key],
            ];
        }

        for ($i=0; $i < count($tfidf_array); $i++) { 
            $cek = DB::table('tfidfs')->where('id_term', $tfidf_array[$i])->count();
            if ($cek < 1) {
                //untuk mengecek jika kata hasil token belum ditampung 
                tfidf::insert($tfidf_array[$i]);
            }
        }  
        // dd($tfidf_array);
        // tf::insert($tf_array);
        return back()->withInput();
    }
    
    public function vectorDoc()
    {
        $samples = [
            'smt secretariat one pt pln persero power generat sector tarahan respons document control process document control form start submiss distribut manual process distribut document hardcopy research creat integr manag inform system simantep onlin integr inform system gave onlin servic document manag pt pln persero power generat sector tarahan lampung inform system made extrem program xp one agil develop method result research onlin inform system access tarahan pltu offic area access internet simantep onlin conveni user term document manag control start submiss valid distribut document onlin servic',
            'phone cell user countless solv problem damag phone paper discuss develop expert system assist identifi damag system forward chain backward chain method search techniqu conclus exist problem system built php program languag mysql databas access mobil browser',
            'dengu fever dbd malaria kind diseas caus extraordinari occurr indonesia diseas spread rapid death short time predict occurr dengu fever dbd malaria diseas bandar lampung process manual present form tabl graph research web base geograph inform system web gis develop provid inform spread dengu fever dbd malaria diseas citi bandar lampung data obtain depart health citi govern web gis implement arcview mapserv mysql databas phpmapscript program languag result show web gis provid inform spread area dengu fever dbd malaria diseas bandar lampung villag level',
            'research discuss simul applic automat coffe machin process make type choic drink coffe milk chocol coffe milk coffe brown mocha chocol milk automat applic concept finit state automata fsa appli recogn captur pattern process make coffe drink applic finit state automata fsa appli read input symbol start state final state order languag recogn machin process accord read languag',
            'technolog develop make human life practic way technolog entertain busi educ technolog educ optim educ process system student listen teacher explain one educ technolog process learn multimedia interact subject solar system one subject taught junior high school topic exoplanet planet solar system seldom teacher junior high school interact multimedia learn process base gave topic solar system paper discus design develop interact multimedia topic solar system exoplanet anim interact multimedia base applic attract user interact',
            'onlin school report card inform system base web mobil inform system give inform servic student academ data form access web mobil onlin school report card inform system base web mobil develop form onlin school report card inform system exist previous research develop purpos onlin school report card inform system give eas student teacher data process simplifi student academ data process deliveri inform pupil network mobil detect influenc onlin school report card inform system base web mobil system user satisfact level research method research base field research method develop system waterfal model softwar window xp profession macromedia dream weaver xampp result research develop onlin school report card inform system conclus questionnair data applic web name onlin school report card inform system base web mobil sma negeri gedong tataan',
            'increas demand comput aspect life peopl interact comput agil one type faster method applic creat peopl type fast applic common paid trial applic altern applic develop handl problem applic develop waterfal method pascal program languag consid easier develop applic open sourc',
            'mathemat comput scienc close relat problem comput scienc problem solv mathemat techniqu concept find nearest distanc minimum fare minimum time determin minimum found mathemat program dynam program consider factor secur road impass vehicl comfort road infrastructur dynam program one method mathemat creat sequenc interrel decis systemat procedur determin optim combin decis paper discuss dynam program appli determin minimum distanc determin minimum distanc applic make easier calcul',
            'research discuss find solut knight tour problem step knight chess board form path track pass point box return origin point box call hamilton cycl produc close knight tour path pass point return origin point track call open knight tour way solv knight tour problem one backtrack algorithm paper discuss find solut knight tour problem vari x x chessboard',
            'gnu linux oper system user build develop oper system two method remast linux scratch differ lfs method build system zero remast exist linux distribut goal research investig step build linux base oper system lfs method oper system built success fulfil daili play music video brows program task',
            'learn nowaday access web browser e learn server name learn manag system lms lms consist paid unpaid tailor version special order research lms paid lot lms paid one clarolin develop e learn develop mobil devic android devic android usag increas year applic e learn underdevelop base background need develop e learn applic android mobil devic android devic support develop client applic e learn purpos develop applic enabl user android devic download file exist lectur materi android devic research applic develop eclips ide java program languag android xml clarolin lms server applic complement clarolin access web browser result research show client applic download file exist lectur materi clarolin server',
            'nowaday traffic control system computer system set densiti chang vehicl research algorithm develop solv problem traffic control constant manual fix time traffic algorithm call miloza algorithm miloza algorithm algorithm find optimum solut traffic control base densiti characterist vehicl purpos research make simul automat control system traffic light densiti characterist three four junction milozaalgorithm altern solut simul applic made visual basic program languag method applic develop prototyp method',
            'indonesia archipelago countri strateg geograph locat ethnic group one preserv art cultur indonesia teach introduc generat start elementari school children taught indonesian art cultur text book learn interest subject deliv monoton convent research interact learn system develop develop web base gis system cover introduct art cultur provinc indonesia data research obtain art cultur book offici websit indonesian cultur system implement softwar arcview mapserv mysql databas php program languag research result show learn system provid inform provinc indonesia base categori select user system display inform form pictur tradit hous weapon video relat cultur system help student understand materi quiz tool',
            'nowaday footbal sport perform human develop footbal offer varieti integr technolog divers form ball type shoe game game footbal great demand peopl pes footbal game game strategi determin success game recent one great team won numer competit barcelona fc club interest featur power win game uniqu featur barca style short pass player basic three main role game footbal player control ball player support movement player ball oppos player snatch ball order find solut address characterist barca style repres game mathemat model design simul strategi game guard one one total surviv strategi strategi barca style',
            'condit locat map university lampung campus map form paper data display form limit map display inform relat exist facil personnel university lampung research design web base geograph inform system gis focus inform lectur depart unit web base gis tool visitor academ obtain inform faculty unit build facil map university lampung web base gis develop open sourc softwar quantumgi mapserv pmapper framework mysql databas pmapper framework increas speed accuraci conveni consist develop applic mapserv',
            'remark develop scienc technolog influenc busi trend one mode busi wide nowaday vend machin vend machin machin serv client food drink costum put money machin slot research discuss applic coffe vend machin make variat coffe optim output money make applic finit state automata fsa appli handl problem recogn languag make drink mow algorithm optim output money transact process',
            'samba applic connect two oper system platform applic creat order comput linux unix bsd samba file server base protocol windowsbas comput access samba server implement laboratori comput fmipa university lampung mean facilit access data file instructor assist practition samba server file share give problem access store manag data file user access system comput network databas server messi problem pdc requir file server one purpos paper appli samba server pdc central laboratori comput fmipa university lampung base test samba server pdc success appli manag storag transmiss data easier problem resolv',
            'develop technolog grow faster requir person institut job quick payrol system one import thing institut calcul employe salari accur quick computer system kantor pelayanan kekayaan negara dan lelang kpknl metro citi institut respons communiti servic process payrol system institut comput semi manual institut computer system calcul employe salari automat research made payrol system integr crystal report finger print attend system payrol system convert data crystal report finger print attend system calcul salari employe addit system cut withhold tax cut delay home time home present detail attend salari recapitul report employe research visual basic program languag mysql databas crystal report perform report method develop system research waterfal method result black box test show payrol system success built user requir',
            'studi implement quin mccluskey method minim boolean function method minim boolean function afris conduct studi appli karnaugh map method tomaszewski appli quin mccluskey method make program simplifi boolean algebra maximum input function variabl initi studi quin mccluskey implement program simplifi input function maximum length variabl input enter direct keyboard result calcul effici applic',
            'music beauti art separ human life play music devot feel emot felt form artist composit enjoy ourself music instrument it characterist guitari one music instrument common genr studi guitar tuner applic android platform develop system built java program languag ide eclips java android plugin devic smartphon android ic ice cream sandwich align tone guitar user',
            'museum institut serv nurtur protect repositori histor object indonesia museum look crowd time school holiday studi tour museum mean learn student research mareov inform provid museum inform inform provid detail inform answer curios visitor need tour guid solut develop improv servic appeal museum make graphic technolog augment realiti ar implement ar museum display inform object museum tour guid result show prototyp applic help provid inform virtual guid visitor',
            'studi discuss complex comparison algorithm prim algorithm kruskal algorithm sollin algorithm graph implement complet graph order n n increment data generat random weight rang c program languag develop sourc code data implement result found algorithm complex',
            'indonesian senior high school polici decid major student match interest talent abil develop auto learn decis support system iter dichotomis id algorithm teacher make decis student major id algorithm part learn techniqu artifici intellig data sampl past train data system give decis support refer teacher major student good decis accuraci requir train data wide variat result show vari train data higher accuraci decis result',
            'nowaday inform longer complementari product major requair institut school dissemin inform school system paper dissemin inform facilit make sms base inform system center announc school activ parent teacher student sms gateway school inform dissemin center sms gateway system implement mysql databas gammu sms gateway tool program languag php',
            'research conduct develop inform system support complet thesi form graphic letter system develop ade menu durat studi settlement thesi recapitul data data guidanc thesi develop aim improv perform secretari depart manag student thesi data develop php program languag function librari kendo mysql databas applic appli inform system depart comput scienc university lampung',
            'research develop verif system cours aim acceler process check exist curriculum comput scienc major verif system develop search string match method two part program method exact match heurist match exact match program method brute forc algorithm shortcom preprocess phase heurist match algorithm n gram similar check detail result show brute forc algorithm quick rune time result n gram similar algorithm effici check differ one charact kopel',
            'semaphor medium communic one person process studi semaphor code difficult requir long time limit medium learn code research aim design develop applic learn semaphor code android base mobil devic provid altern learn media semaphor code learner android mobil devic select basi applic develop android technolog applic develop java html css javascript program languag data motion pictur semaphor applic test carri blackbox test method test examin function featur applic test result system applic test show semaphor code applic run android oper system appli ice cream sandwich jelli bean version translat word sentenc roman letter motion pictur semaphor',
            'increas number size data result increas user abil comput process larg data paradigm parallel comput propos handl problem teach substanti job split margin job increas number worker one method cluster comput one processor handl singl process show signific increas comput convent one high price build cluster system constraint studi one parallel comput method gpu comput compar result cluster comput gpu comput graphic process unit gpu comput parallel result studi show gpu comput processor maxim show capabl matrix multipl cluster comput',
            'academ supervisor collag finish studi effici depend condit potenti alloc academ supervisor comput scienc major manual make data process late studi develop web base system determin academ supervisor collag repres graph system develop php program languag system output colleg data academ supervisor graph colleg data supervisor system repres data result format sk propos format system develop prototyp method advantag prototyp method system develop expect user prototyp produc function login lectur colleg data input clear data academ supervisor edit data academ supervisor devid colleg data supervisor prototyp produc addit function print complet supervisor data print supervisor data show supervisor data graph',
            'muslim reason learn hadist statement activ phophet muhammad guid life muslim object research develop android base applic hadist riyadhus shalihin book vol imam nawawi english riyadhus shalihin field meadow android develop tool eclips droid draw button placement develop creat applic applic java xml program languag applic made mobil phone base android oper system featur applic abil choos theme base theme riyadhus shalihin book vol basic knowledg hadist imam nawawi imam nawawi biographi develop exit menu exit applic',
            'tree structur wide repres problem daili life comput scienc technolog one method repres tree blob code research develop applic encod decod tree blob code applic aim simplifi process chang tree blob code encod process chang blob code form tree decod',
            'provid educ lectur oblig carri research dedic societi research dedic one requir increas lectur function posit assess refer depart accredit data process lectur research dedic comput scienc depart spreadsheet spreadsheet thas process data research dedic time resourc effici studi conduct develop inform system lectur research dedic comput scienc depart manag tool depart data process lectur research dedic system develop method ration unifi process rup rup collect practic softwar develop industri rup six main workflow busi model requir analysi design implement test deploy lectur research dedic inform system comput scienc depart develop rup rup method provid flexibl programm develeop softwar',
            'nation exam method test educ indonesia determin student graduat senior high school determin graduat base minimum obtain student establish govern student follow simul exam face nation exam capabl test purpos research develop simul system nation exam senior high school student web base access onlin addit purpos research build altern simul exam school develop system program languag php mysql databas test system system function proper process simul test result right finish work problem simul test',
            'exam test evalu goal determin one abil trial conduct school general convent implement paper last correct process correct process facilit make onlin examin system school system develop method waterfal method process method waterfal process sequenti system superbl produc system access three user administr teacher student develop onlin examin system school facilit teacher term correct direct assess process onlin exam test system implement databas mysql php program languag',
            'samba implement smb server messag block cif common internet file system window nt provid share access file printer miscellan communic node network samba client server mechan comput act server activ provid servic user altern wireless router replac comput server function provid servic comput server openwrt wireless router instal samba server simplifi instal configur process samba server design bash shell script automat instal configur samba server openwrt router',
            'doubt technolog support human life appli aspect educ develop learn process comput aid tool common applic sequenti search algorithm search process inform learn system human heart applic comput educ one solut learn process teacher lectur student research sequenti search algorithm search process learn system human heart one search method facilit text search inform requir user system inform materi relat heart organ e heart anatomi heart physiolog heart patholog test system applic black box method black box test focus function requir softwar black box test test system order system work',
            'onlin attend list one breakthrough record list attende comput technolog internet general onlin attend list enter usernam password regist system limit area research develop web base attend record system login area restrict system check usernam password enter user detect ip address allow access system user succeed login system mean list presenc list fail system record presenc system produc report attend list period protect inform access',
            'nowaday compani larg small flock scienc technolog support compani oper process studi made softwar servic inform system centr easili run applic park lot facilit corpor leader monitor park report result time softwar design adob flash creat visual basic program seagat cristal report microsoft offic access databas softwar park system expect term secur speed order time park park area',
            'scienc studi natur it surround includ flora fauna flora plant live habitat area fauna anim live habitat area elementari school level student learn introduct flora fauna characterist environment habitat breed food type time elementari school student introduc flora fauna book research web base bilingu teach system flora fauna indonesian english develop elementari school student system built waterfal method mysql databas php program languag system creat aim student flora fauna surround environ increas student knowledg english',
            'secur import aspect digit data transmiss steganographi one hide secret messag awar exist messag research built digit steganographi system web base adapt minimum error signific bit replac amelsbr method media hide messag form jpg output form png messag form txt procedur appli amelsbr method capac evalu minimum error replac error diffus result show amelsbr method manag hide restor file caus excess distort nois stego imag amelsbr method possibl return file imag manipul chang imag bright contrast black color domin rgb white color domin rgb',
            'activ student comput scienc system system develop base academ inform system siakad manag university lampung system built calcul number activ student base data input student krs begin semest krs data build system calcul number activ student major comput scienc system develop php mysql test black box white box test',
            'markerless augment realiti ar term environ combin real world virtual world object real world recogn posit direct locat markerless ar technolog interact visual inform technolog combin mobil communic devic smartphon android oper system camera featur internet acceleromet digit map gps global posit system android devic integr ar technolog implement studi markerless ar technolog implement faculty mathemat natur scienc scienc university lampung build aim studi creat applic inform build show faculty map inform natur scienc access android smartphon applic design implement java program languag android beyondar framework support detect display ar object googl map api display map scienc faculty result data test equival partit test shown applic function analysi addit base questionnair data applic user friend applic averag good interact averag good',
            'peopl inform geograph relat object distanc region object locat facil natur resourc geograph inform system gis solut inform geograph relat object object research lampung culinari lampung provinc typic food culinari place young peopl teenag parent gather culinari place categor caf restaur applic develop microsoft visual basic express mapwingi activex control develop applic implement water fall method support softwar gps coordin quantum gis microsoft access databas',
            'communiti servic program one three core servic tri darma coordin lpm university lampung larg number lectur follow lpm program need special strategi provid mean dissemin inform one technolog popular cell phone user sms short messag servic natur sms practic econom effici deliv inform sms technolog peopl cheap messag deliveri research inform dissemin system sms gateway implement gammu combin php program languag mysql databas result implement system help easi lecturer obtain inform program communiti servic join',
            'moslem peopl read qur children adult addit activ conduct adult shame main reason one learn read quran iqro iqro form book wide interact make immedi bore learn android oper system mobil devic wide android open sourc make it applic easier develop applic iqro integr smartphon tablet pc base android user learn anytim research aim creat applic iqro good view easi easi learn fun user addit aim research introduc peopl inform technolog android result research applic iqro android base altern learn medium studi iqro make interact provid easi applic learn',
            'research develop web base applic php generat develop aim produc applic minim time make web base inform system applic built php program languag integr mysql databas support javascript jqueri research method research action research prototyp it softwar develop method applic generat php file add view edit delet search data store databas entir data tabl filter data display form input element user generat file textfield textarea password radio button select option checkbox addit applic complet data valid function file generat',
            'sms gateway servic receiv massag send counterpunch automat number purpos sms gateway one research sms gateway quick count pilkada research autorepli featur ade send sms autorepli add modem concept send sms modem modem card oper system receiv inbox repli automat modem send receiv data oper cellular servic oper cellular autorepli featur ade ensur acceler acquisit inform reach sound time perform elect modem reduc toll rate system repli sms accord servic provid sender',
            'school inform import associ parent school inform student school test score mean academ activ import parent test score inform inform report parent system develop report student test score sms gateway work proper sms gateway system creat mysql databas gammu php',
            'technolog implement spread subject librari librari develop rapid dynam manual system felt don t useabl handl job daili activ procur catalog situat control situat requir inform system base comput technolog cbis comput base inform system studi inform system base web design php mysql program languag system expect handl user find inform librarian book data process result studi implement librari inform system base web school bandar lampung',
            'librari inform system system requir librari manag data transact librari interact inform system import improv qualiti librari dissemin inform effici internet mobil phone research develop system inform librari interact member visitor librari allow member inform book quick implement system sms gateway autorepli facil member obtain inform book everytim develop system purpos simplifi process updat inform book member librari add read interest increas number visitor librari implement system mysql databas gammu tool sms gateway php program languag',
            'develop technolog comput human be compet creat newest technolog form hardwar softwar big compani comput base inform system cbis pt pln persero lampung region pt pln persero lampung region compani suppli electr lampung provinc activ mail receiv letter receiv letter accommod secretari document archiv comput semi manual manner comput system effici amount letter mistak slow regist caus slow search archiv generat recap letter report letter document secur absent exist system develop improv servic good secur research goal make system letter document secur featur login good encrypt decrypt twofish algorithm research visual basic program languag combin mysql databas crystal report system develop methodolog research waterfal black box test result show letter archiv system success develop accord user',
            'asset manag import aspect compani pt pln asset manag includ record keep mainten environ manag record inform asset own compani primari import thing inform obtain record data asset compani asset asset own compani record asset compani optim asset compani easier undertak asset manag pt pln persero tarahan generat sector steam power plant asset spread insid build domin equipmen site plant generat engin process record inform asset pt pln persero tarahan generat sector manag computer system process record asset manual reliabl research develov asset inventori inform system featur registr inform detail attribut monitor mape locat exist asset research cover fix asset exist util build web base gis asset inventori inform system built mapserv pmapper pmapper framework base mapserv php mapscript pmapper good set tool readi plugin api add function tough framework run web browser aplic expect facilit compani determin monitor it optim',
            'research conduct languag learn advisor com japanes ninth beauti spoken languag russian finnish fourth beauti write languag arab chines french masaki tani public relat japan embassi indonesia indonesia countri world learn japanes china south korea learn japanes foreign languag formal institut lectur cours home book internet general japanes devid two categori formal inform languag formal languag wide applic includ usag close peopl foreign inform languag close peopl includ famili research made applic japanes learn base web php macromedia dreamweav hope applic easili peopl don t japanes sequenti algorithm applic find string',
            'linux open sourc oper system chang develop linux oper system need research linux oper system generat oper system support learn java program result oper system run live cd live usb oper system success creat learn tool java program',
            'track siakad transcript found bug arrang lesson posit tenth till fifteenth semest locat semest process track bug simul program research carri develop origin program siakad simul program analyz find bug system develop program php mysql databas softwar track bug whitebox test method determin bug procedur structur program oper function system bug repair ade sourc code program determin variabl memori variabl applic bubbl sort method sort proccess result show bug program fix',
            'select good achiev lectur recognit lectur conduct three principl higher educ good result select lectur university lampung conduct annual process select university appoint assess team conduct assess candid assess process conduct manual time process data addit assess subject natur relev real condit base background object research build decis support system conduct select process good achiev lectur university lampung decis support system built base web php program languag mysql databas simpl addit weight method make decis method determin point valu criterion rank score made determin solut test applic black box show system run proper result influenc academ atmospher institut qualiti',
            'diseas unwant problem freshwat fish cultiv lack inform prevent diseas limit number expert field fisheri unoptim harvest lead substanti loss base background expert system diagnos diseas freshwat fish cultiv made research system intend fish cultiv identifi diseas base symptom fish treatment appli cure fish expert system web base php mysql program languag databas forward chain method reason method decid rule execut execut process repeat result system diagnos diseas kind fish test research intern extern result test show system diagnos meet expect manag rule system run intend research creat web base expert system applic diagnos diseas base fact give solut expert',
            'biolog part scienc special characterist scienc term object issu method term object form kingdom kingdom animalia anim world planta plant world monera protista fungi object number thousand type it difficulti learn one make easier learn group classif organ call taxonomi scientif call binomi nomenclatur studi learn classif scientif nomenclatur creat taxonomi applic base android aim facilit student classifi organ consist kingdom kingdom super divis divis class class ordo famili genus speci applic design implement java program languag android mysql web server manipul data need updat databas period result data test equival partit test shown applic function analysi addit base questionnair data applic user friend applic averag good interact averag good',
            'studi develop decis support system dss util analyt hierarchi process ahp method process select employe acquisit select process criteria multicriteria choos applic accept decis support system dss help manag human resourc hr decid applic select applic criteria weight criteria applic modul busi process generat rank applic base final amount applic greatest rank mean applic criteria accord compani requir addit web servic busi process payrol modul process set function method contain server exchang data inform oper system applic internet xml format messag deliveri system construct php mysql languag result function test data base test case test show system work accord it analysi',
            'research conduct make applic chang chomski normal form cnf greibach normal form gnf substitut method one repres context free grammar cfg greibach normal form gnf develop applic start make algorithm prototyp method chang chomksi normal form cnf greibach normal form gnf applic chang chomski normal form greibach normal form result show applic chang chomski normal form greibach normal form smooth input applic chomksi normal form cnf',
            'secur confidenti data transmiss two import aspect era open data access inform one secur data transmiss steganographi research method end file eof imag medium messag hidden insert end line imag file media hide messag form jpg output form png input messag manual test conduct type imag resolut number messag charact charact result show method end file eof success hide messag media imag back messag insert imag size number charact affect process time embed extract crope contrast bright stego imag conduct analyz resili messag insert result show stego imag manipul sensit resist manipul make messag corrupt recommend manipul stego imag',
            // 'knapsack problem kp one combinatori optim problem maxim amount profit prioriti item chosen put sack bag exceed it capac kp appli field cargo load budget control financi manag addit kp includ np hard problem research conduct heurist method solv method branch bound greedi algorithm dynam program genet algorithm ga feasibl chromosom one critic issu optim problem constraint function chang feasibl solut ga strategi handl case evalu step evalu calcul chromosom fit handl infeas chromosom presenc reject strategi repair strategi penalti strategi paper develop strategi ga approach chromosom repair strategi order perform algorithm numer experi test problem literatur addit studi give variat ga paramet determin effici effect algorithm',
            // 'list academ advisor util util develop administr process comput scienc major previous bubbl sort sort algorithm compar bubbl sort algorithm select sort quick sort determin quickest algorithm implement major search worst case slowest execut time unit case fastest execut time unit result quick select bubbl select sort algorithm proven quickest n cadem advisor data util research quicksort algorithm fastest algorithm',
            // 'research develop applic encod decod tree dandelion code aim obtain dandelion code tree encod decod encod generat code tree decod process code back tree applic develop produc tree represent addit research give problem dandelion code solv lowest cost electr instal',
            // 'build client applic server sale electron puls puls distributor compani maxrefil minim failur rate transact process applic instal run proper android devic version ic jelli bean kitkat success rate applic provid conveni sale puls good percentag good success rate applic minim failur sale puls good percentag good',
            // 'research conduct make system call inform system kkn pigeon hole method determin group kkn particip group kkn particip base gender major faculty group kkn particip base gender kkn group consist particip consist male femal group kkn particip base major faculty kkn group consist particip consist max particip major max particip faculty result group kkn particip base assumpt percentag system accuraci classifi particip kkn ideal percentag group data inform system kkn make prototyp method part sdlc inform system kkn mean student particip kkn process inform system kkn pigeon hole method make placement group kkn particip easi order',
            // 'research make applic simplifi context free grammar cfg process make applic start design algorithm extrem program method system develop method applic simplifi context free grammar cfg test method test applic black box test result show applic simplifi context free grammar smooth simplifi context free grammar step step context free grammar cfg simplif three stage elimin espsilon product elimin unit product elimin useless product three stage sequenti',
            // 'research develop print util lectur attend web base matahari due databas languag siakad academ system inform university lampung util reus solv problem research success develop print util lectur attend list case studi develop comput scienc fmipa university lampung make attend list data sourc result import excel file format dnk list student class retriev siakad university lampung develop improv attend list printout output dynam file custom user requir',          
            // 'augment realiti ar term environ combin real world virtual world object real world recogn posit direct locat one ar technolog develop markerless augment realiti technolog user longer marker display digit element technolog visual inform interact technolog integr mobil devic smartphon android oper system camera featur internet acceleromet digit map gps global posit system android devic integr ar technolog implement research markerless augment realiti technolog implement detect find locat nearest gas station bandar lampung citi applic design implement java android program languag ar framework detect display ar object googl map api display map locat gas station result test data equival partit test applic function accord analysi addit base questionnair data applic user friend applic averag grade excel interact averag grade',
            // 'research develop applic simplifi context free grammar chomski normal form softwar made complet process simplif context free grammar cfg user identifi stage process simplif easi understand simplif stage speed process softwar creat php program languag context free grammar cfg input process order carri start remov epsilon unit useless product result chomski normal form black box test result method partit softwar equivalen suggest simplif softwar context free grammar chomski normal form php success suitabl user',
            // 'inform system ppa scholarship registr depart comput scienc faculty mathemat natur scienc lampung appli sort algorithm quick sort select sort system eas process shcolarship data student particip select ppa scholarship registr depart comput scienc enter registr data system secretari depart doesn t enter applic scholarship registr data anymor system rank automat two sort method quick sort select sort system built php program languang mysql base rune time sort complex it algorithm prove quick sort method faster select sort method result function test data show system function accord requir analysi base fuction test data show admin level system includ categori good averag user student level system includ categori good averag',
            // 'research develop household inform system base websit household inform system creat divis custom data inform system pt pelabuhan indonesia ii persero tanjung priok divis handl issu relat applic network multimedia inform system creat resolv issu relat submiss procedur damag handl submiss procedur item user effici util time effort cost perform procedur inform system creat php program languag sequenc process perform start enter data submit approv stakehold print data approv result black box test system equival partit method show system function accord user requir',
            // 'citizen data record shown satist famili number educ religion gender occup age group tabul system demographi data karya penggawa subdistrict manual method record amount citizen data offic excel constraint citizen data record difficult accur inform citizen data consum time check report citizen data word tabul demographi data optim constraint inform system demographi karya penggawa subdistrict need system web base system main function form tabul demographi show statist citizen data gender educ occup religion age group graphic addit system make quarter report demographi choos one report format print excel minim human error make work staff karya penggawa subdistrict easier process demographi',
            // 'system search engin repositori softwar desktop creat aim facilit user softwar instal user window oper system macintosh system download check latest version softwar system creat freewar softwar window macintosh oper system linux system display generat sourc list debian research system made extrem program web extract techniqu extract data websit www filehippo com',
            // 'educ institut vocat high school period carri admiss student exist decis support system expect assist school select student method construct dss one method ahp ahp one dss method handl multi criteria select result dss ahp method sampl data find biggest criteria factor select result studi ahp method implement inform system student admiss make decis placement depart base abil student select depart',
            // 'nowaday develop inform technolog grow rapid stope communic problem recent distanc anymor feel close technolog develop scienc technolog emerg technolog develop gps global posit system studi research design build android base applic find public transport rout bandar lampung call balam tran util gis technolog applic search rout citi transport bus rapid transit brt citi bandar lampung user longer difficulti find public transport destin locat migrant citi bandar lampung addit applic display inform public transport brt rout broaden user knowledg applic develop dijkstra algorithm find shortest rout conclus studi applic balam tran success construct user balam tran applic prove questionnair applic test achiev good',
            // 'radio stream technolog send audio file simultan multipl comput network small data packet generat output semi real time process stream music file sound comput start receiv music file data shoutcast dnas freewar stream radio technolog shoutcast user provid internet radio privat server softwar provid winamp multimedia player devic creat nullsoft trial result studi applic stream radio user display program content radio play lay radio stream display e mail address websit stream encod affect occurr delay connect unstabl caus disconnect automat processor speed speed internet connect affect buffer stream player player',
            // 'smartphon primari modern era featur advantag offer smartphon smartphon wide peopl support activ accomplish work develop technolog gis geograph inform system provid servic locat posit place locat it user satellit signal gps provid precis accur inform posit speed direct time studi research design build android base search applic call atm map util gps technolog applic search locat atm citi bandar lampung atm citi bandar lampung turn user avoid difficulti find locat atm great resid citi bandar lampung applic display transfer code bank assist user make easier cash transfer applic develop util pars techniqu xml pull parser compar data store folder asset project server conclus studi reveal atm map success construct proven it user excel rate comment satisfact play store applic data store project asset folder purpos reach easi quick access hand data updat applic process easili',
            // 'system result applic final project comput thesi scienc student creat applic perform depart applic made util proper present store librari contain place attach applic provid difficult user sete connect ftp server studi built applic manag system am one solut implement overcom problem am softwar organ content am facilit applic display content main websit implement am content websit php program languag mysql databas test result equival partit ep show am built facilit user post applic applic embed websit am run proper',
            // 'excess plugin practic creat web base web attach plugin small storag capac plugin seminar built advantag system exist convent system whiteboard paper plugin success implement web comput scienc depart dissemin inform seminar schedul optim plugin facil remind lectur colleg student relat seminar',
            // 'inform goegrapich condit need stakehold distanc inform locat requir inform inform geograph inform system gis geografi sig one solut aquir geograph inform tourism kabupaten pesisir barat object materi research thesi reason kabupaten pesisir barat good potenc tourism present tourism destin domest tourist intern tourist tourism destin divid marin tourism wisata marina water tourism wisata tirta histor tourism wisata sejarah natur conserv tourism wisata suaka alam sig web basi made php program data googl map waterfal method support softwar gps coordin xampp server',
            // 'inform secur encrypt process cryptographi techniqu research discuss compar analysi symmetr algorithm tini encrypt algorithm tea loki term complex time speed perform test conduct data size vari byte byte thirti data test time result show tea faster encrypt decrypt compar loki complex algorithm linear algorithm',
            // 'develop educ game motiv low interest desir peopl learn aksara lampung case requir learn system attract pleasant attract peopl follow develop exist technolog one learn system media educ game research extrem program method design unifi model languag uml applic test black box test method approach equival partit ep calcul likert scale side base result obtain questionnair applic user friend applic averag excel',
            // 'meet patient expect one requir health care qualiti order achiev healthcar qualiti hospit chang paradigm drug orient patient orient main problem health care qualiti today patient wait long time servic increas morbid risk socioeconom cost solut resolv long servic time implement queu system predict actual queue situat studi aim estim wait time patient network queue jackson method build queu model singl server consist health care facil registr consult point patient pharmaceut cashier method rule serv consum move one workstat workstat leav system network queue jackson server node provid independ servic analyz node separ',
            // 'fish diseas undesir fish farmer low product high mortal aquacultur lack inform diseas prevent method small number expert caus farmer suffer loss expert system need diagnos diseas fish system aim cultiv resolv fish diseas problem research expert system base android built java program languag databas forward chain method determin role execut rule execut process repeat result system diagnos diseas speci fish data test result equival partit show manag rule system run base it function system diagnos diseas base questionnair data applic user friend applic averag excel',
            // 'librari part learn resourc own school colleg learner easili find inform knowledg librari develop technolog make peopl work effect effici one make convent system computer system websit util facil connect internet librari effect effici search order book research design web base inform system program languag php mysql system expect address user search book book facilit administr school circul borrow book prepar report research conduct result author implement design librari inform system web base sma negeri penengahan',
            // 'school institut specif design teach student supervis teacher school basic tool implement educ expect make advanc societi system aim altern media facilit student peopl inform senior vocat high school locat bandar lampung studi mape achiev senior vocat high school bandar lampung built base android java program languag method applic waterfal design unifi model languag uml applic includ school bandar lampung consist senior high school sma vocat high school smk applic varieti data school achiev nation examin school address school pictur coordin point school telephon number assist user find inform locat high school vocat school bandar lampung result test perform equival partit method manag rule system run proper it function system provid inform proper addit base test data applic includ categori user friend applic averag percentag good',
            // 'nowaday internet grow rapid includ indonesia internet essenti requir inform purpos benefit internet make peopl internet applianc support activ internet impact increas demand internet servic provid internet provid compani engag provid internet servic process instal troubleshoot internet network provid requir manag inform system organ divis task handl instal troubleshoot internet network internet provid manag system difficulti devid task employe handl process instal troubleshoot internet network research creat manag inform system instal troubleshoot internet network servic reciev inform internet interfer custom handl process divis task instal troubleshoot internet network manag inform system creat php hypertext prepocessor plugin twitter bootstrap high chart result black box test show manag inform system instal troubleshoot internet network servic success creat user',
            // 'geograph inform system gis inform system input process generat geograph geospati referenc data support decis make plane technolog android base smartphon internet acceleromet digit map gps global posit system android integr gis implement studi gis technolog implement detect find locat health care provid citi bandar lampung applic design implement java program languag googl map api show locat health care provid test phase equival partit method function applic rune accord analysi',
            // 'comic general term denot phenomenon imag put sequenc side side form physic book order follow develop digit technolog digit comic born comic reader applic creat digit distribut read comic applic yii framework yii php base framework mvc architectur pattern',
            // 'technolog develop posit impact deliveri news inform public easili quick distanc limit access news support develop internet access increas rapid world access share inform real time lag time news portal daili lampung newspap base android applic conveni public obtain inform news news portal base android provid news websit lampungnewspap citm co id thing studi thesi connect android platform websit databas platform librari volley news portal made android studio retriev data databas websit lampungnewspap citm co id waterfal method',
            // 'bird one anim found di habitat it exist spread forest plantat urban bird one group anim close human life aim system altern media facilit peopl inform type bird lampung university research android base field guid type bird lampung university area java program languag method applic waterfal unifi model languag uml design applic type bird found lampung university data applic pictur bird bird latin english famili descript status user recogn type bird find lampung university data result equival partitioningtest show rule system work suitabl function system give inform applic categori variabl user friend averag categori interact variabl averag',
            // 'rice plant diseas identifi symptom find rice plant diseas agricultur expert number distribut agricultur expert limit overcom problem optim system knowledg expert requir research design web base expert system forward chain method certainti factor data process system diseas diseas symptom system develop superior easi access user friend display certainti level diagnosi result custom satisfact',
            // 'distanc student data store student address address high school student pringsewu district distanc research conduct determin classif distanc store high school student pringsewu district distanc student classifi obtain five categori sangat dekat dekat sedang cukup jauh jauh eight attribut nomor sma kabupaten kecamatan kelurahan jarak asal smp class classif perform naiv bay weka tool distribut train data test data time test result highest accuraci naiv bay distribut train data test data data student address inform classif result display form digit map mape student address high school pringsewu district',
            // 'data bank system place store data comput scienc depart data bank system store data student score comput scienc depart system student data bank score comput scienc depart manual form system student data bank score comput scienc depart manual system develop digit system search system student data bank score comput scienc depart digit form appli brute forc algorithm brute forc algorithm string search algoritm charact check method pattern charact text data search system student data bank score comput scienc depart test case test focus function system data search system student data bank score comput scienc depart brute forc method ten peopl random chosen questionnair result system test likert scale result final score state system student data bank score comput scienc depart brute forc method good',
            // 'local govern financ region asset lampung tengah regenc govern agenc engag field financi manag region asset employe resourc import determin success work unit effort improv qualiti perform public servant posit increas incumb posit level civil servant incumb increas award work perform dedic civil servant decis support system facilit determin employe worthi incumb increas studi calcul method decis make simpl addit weight criteria echelon work live civil servent work live echelon work live incumb educ train system built php program languag test result black box test questionnair show system function user system deem criteria incumb increas structur civil servant local govern financ region asset lampung tengah regenc',
            // 'earli nowaday internet servic provid grow rapid choos internet servic provid extrem difficult internet servic provid it advantag disadvantag method failov connect classifi round robin combin internet connect multipl internet servic provid failov method separ internet servic provid prioriti connect backup connect applic two internet servic provid cooper internet connect remain stabl applic longer difficult handl problem bad internet servic provid',
            // 'larg amount data affect search process data databas greater amount data data search process longer process manag activ data activ overlook manag system list activit student grade manag record process make amount data increas search time substanti time prior research explain relat oper combin index strategi queri perform compar method research index strategi nest queri activ list inform system student grade data process system test conduct experi compar data index data index tabl scan condit data amount nest queri test regular queri queri test direct amount data appli subject data search index strategi appli big amount data generat faster execut time search index strategi contrari small amount data index strategi affect search process nest queri subject data search faster search time regular queri queri direct',
            // 'recip need mean guid peopl prepar ingredi cook make serv food day smartphon user android domin oper system android base smartphon easi peopl choos case base reason cbr problem solv approach emphas role prior experi problem solv util similar problem solv studi design build recip recommend app android appli case base reason method applic provid recommend recip indonesian food made input ingredi step step cook present evalu show applic success construct user',
            // 'research design implement market basket analysi mba method e commerc applic one expand sell ananda shop implement market basket analysi method mba determin product bought custom one time analysi custom transact list e commerc applic suggest relat product match product chosen custom custom easili product bought system built php mysql languag result function data test base test case show system work proper it purpos',
            // 'util gps global posit system technolog telecommun tool stimul locat base servic lbs smartphon servic inform access mobil network geograph locat research applic design creat give inform friday prayer schedul bandar lampung includ inform mosqu preacher charg mosqu show nearest mosqu locat radius km applic consist android mobil applic user web base system administr connect web servic studi show creat applic give inform friday prayer bandar lampung test result black box equival partit show applic run accord requir',
            // 'hadith narrat bukhari hadith question valid level indonesian societi peopl muslim desper inform fast hadith relat bukhari life advanc peopl find inform relev spread hadith bukhari hadith infromasi accord origin communiti open book thick take long time order acceler facilit public bukhari hadith access daili life day search inform system develop hadith narrat bukhari studi design web base inform system call inform system develop search hadith bukhari design program languag php mysql inform system expect answer problem occur communiti access hadith easi short time',
            // 'zakat infak sedekah manag regul law republ indonesia number includ plane collect distribut util manag institut accord islam shari trust benefit justic rule law integr account one object lembaga amil zakat laz colect manag deposit zakat infak sedekah zis muzakki distribut util mustahik accord islam shari develop system inform zis zakat manag laz manag account transpar zis system inform easier offic zakat data process provid public general muzakki direct exist inform speed process present inform collect util zis',
            // 'cocoa theobroma cacao l one plantat commod attent it role high nation economi lampung provinc one central cocoa plantat indonesia cacao cultiv wide insepar varieti obstacl pest plant diseas identif pest diseas symptom found cocoa plant know control difficult farmer area solv problem quick accur limit expert make handl pest diseas cocoa plant difficult requir expert system identifi pest diseas cocoa plant control base knowledg provid expert identifi pest diseas cocoa control research appli calcul method theorem bay calcul percentag calcul result percentag user direct pest diseas control identifi test research method black box equival partit ep test questionnair divid variabl user friend variabl interact variabl test user friend variabl obtain averag rate interact test variabl obtain averag rate test result system show app feasibl',
            // 'student time attend lectur student forget task prepar exam impact achiev obtain maxim research research built mobil applic android base smartphon oper system remind student comput scienc faculty mathemat natur scienc university lampung applic made two part web base admin android base user connect web servic applic made support maxim lectur activ work applic alarm remind remind student lectur task exam schedul test result black box equival partit show applic run accord requir',
            // 'cryptographi process messag specif algorithm make messag difficult understood steganographi hide messag form research modifi hill cipher signific bit lsb build web base applic send messag origin messag android applic titl cipher key automat applic recipi email applic organ compani stego cover file type jpg messag file type txt modifi hill cipher lsb method encrypt messag emb imag reveal origin messag research show lsb method possibl reveal messag imag manipul chang bright contrast cover grayscal domin red white colour imag qualiti',
            // 'activ communiti servic program kuliah kerja nyata kkn lampung university intracurricular activ combin implement tri dharma perguruan tinggi method provid learn work experi student communiti develop activ group student kkn perform greedi algorithm find accuraci group student kkn analysi base criteria gender faculty school analysi group refer view accuraci random kkn group analysi group student kkn carri three categori regular fkip combin regular fkip',
            // 'secur import thing send secret messag way send messag safe one steganographi research wil compar dynam cell spread dcs spread spectrum method base web applic imag media hide secret messag file format jpg input cover png output stegoimag txt data insert test manipul bright contrast crope result show spread spectrum method dynam cell spread dcs method spread spectrum method return messag bright contrast manipul crope stegoimag right area bottom imag risk lose data depend crope scale imag resolut',
            // 'research conduct creat servic learn activ report system assist bp kkn dpl student manag report kkn data research consist data dpl student locat kkn data kkn unila period e dpl lectur faculti student locat dpl data test system report activ student upload receiv direct dpl respect student data test system student regist particip kkn token login applic villag find placement student dpl villag result show function test black box method equival partit ep result expect test scenario test class ownership smartphon questionnair test provid peopl respond consist student kkn period show ownership android base smartphon occupi highest number e ownership provid telkomsel occupi highest number e test questionnair android applic respond consist student kkn period show result statement keusion obtain averag categor good test questionnair web system respond consist dpl kdpl kkn period show result statement questionnair obtain averag categor good',
            // 'faculty mathemat natur scienc university lampung institut manag it personnel process employe data process manual make hard find common data personnel file personnel file left separ studi author build web base inform system develop process data process quick method develop make system waterfal result studi built webbas human resourc inform system employe data process activ quick accur',
            // 'inform technolog grow rapid great influenc improv effieci effect busi activ develop compani case inform technolog audit need ensur exist inform technolog satisfi common control standard abil compet busi world purpos research evalu assess make recommend base analysi matur level car dealer xyz bandar lampung framework cobit standard cobit paramet aid assess enterpris matur model research find result matur level car dealer xyz bandar lampung show inform technolog updat procedur polici effect monitor correct action',
            // 'order process sort data rule arrang regular accord rule basic two kind common sequenc rule ascend descend sort data consist criteria prioriti criteria data sort prioriti criteria sort initi criteria prefer criteria criterion addit criterion studi research develop algorithm sort data prioriti algorithm bubbl sort select sort develop multidimension arraylist sort multi prioriti data test conduct five time experi data sort descend data consist four fictiti data one real data list criteria ppa scholarship recipi mipa faculty university lampung result test perform solut found select sort algorithm compar bubbl sort algorithm multi priotiti data sort',
            // 'present era support busi process university requir support ict improv qualiti educ servic analysi exist condit ict implement unila zachman framework show chang applic domin thing immedi correct field work dispers work unit applic requir analysi obtain interview oberv give questionnair intern extern custom conduct document studi strateg plan unila result research plane unila applic architectur accord organiz purpos give input manag prepar integr applic futur',
            // 'tutor formal institut serv student educ school hour cours tutor necess student colleg student rang kindergarten kindergarten elementari school sd junior high school smp high school sma colleg bandar lampung citi cours tutor scatter district bandar lampung citi cours tutor peopl difficult find inform place tutor built applic distribut tutor bandar lampung base android applic built waterfal method applic user map distribut tutor area bandar lampung inform place tutor cover address inform contact cost user search nearest tutor applic test black box method result research data questionnair conclud applic percentag belong categori respond select random',
            // 'al kautsar high school al kautsar foundat implement system govern determin educ emphas educ islam arab student ade report card end semest score teacher respons subject class taught score student submit homeroom teacher arrang report card format homeroom teacher hand admin print report card system mistak occur file admin receiv immedi handl mistak contact homeroom teacher respons teacher correct mistak hand back homeroom teacher admin base background identif problem present research creat system handl problem system web base score data fill teacher direct view accept supervis admin teacher score chang time end semest test result email report card system parent student succeed main featur system fill grade teacher homeroom teacher print report card format work',
            // 'research conduct creat inventori system upt upt lt sit facilit data collect check good laboratori research data consist user menu data chemic laboratori tool analyt servic qualiti document custom data visitor data user data test system login system result show function test black box equival partit ep method obtain result expect test scenario test class result system test method conclud system rune succeed test intend determin function system rune function system user perform lt sit upt process data avail good applic inventori inform system good procur develop respons user asset own upt lt sit university lampung emerg sens respons care data good',
            // 'lampung provinc tourist attract wide public limit inform receiv spread internet network lampung provinc caus tourist difficulti access inform research develop mylampungguid app travel guid base android research conduct aim facilit tourist find locat inform tourist attract lampung provinc addit applic nearest termin station list hotel lampung provinc mylampungguid applic develop v model method app map osmdroid librari softwar applic java program languag sqlite manag databas server adob photoshop cs interfac creation imag attribut app connect internet network result research mylampungguid app cn access inform locat tour lampung provinc connect internet network suggest research improv rout map view display polylin',
            // 'offic equip refer tuli kantor atk barang habi pakai bhp requir provid consid it agenc depart comput scienc depart comput scienc microsoft excel handwrit process data atk bhp start record incom atk bhp it distribut comput scienc depart requir system support process data process distribut atk bhp develop system expect report atk bhp distribus system develop method chosen research waterfal model system design research unifi model languag uml process make program code php program languag html mysql databas test case approach research black box test equival partit ep method result test show function system rune accord it function',
            // 'research conduct make inform system medic specialist base android bandar lampung locat base servic technolog data research data medic specialist inform list medic specialist bandar lampung medic specialist locat practic schedul practic locat contact practic latitud longitud practic locat funcion test equival partit method creat applic show page splash screen page main menu page locat menu page specialist menu page medic list menu map page page give inform medic specialist list medic specialist locat practic schedul practic bandar lampung applic success run android jelli bean kitkat lollipop marshmallow version funcion test likert scale responden societi obtain score averag total user friend variabl score interact variabl includ good categori responden medic student user friend variabl success categori interact variabl good categori',
            // 'lectur activ inform system siksen depart comput scienc unila experienc data attack hack result data lectur activ inform system attack data occur one result lack data secur inform system comput scienc depart one secur data cryptograph techniqu cryptographi encod origin messag elus random messag cryptographi two main process encrypt decrypt encrypt repres convert origin messag password messag decrypt process return password messag origin messag method rivest code rc method one method encod result ciphertext charact length origin messag plaintext method consist three main stage ksa key schedul algorithm prga pseudo random generat algorithm xor process data studi data unila comput scienc student amount student data consist column data test test number origin messag charact password data success encrypt decrypt test result obtain data success encrypt data success decrypt number charact origin messag password',
            // 'pt pln persero pt electr compani state one compani bumn state own enterpris charg electr sector indonesia state asses employe perform compani impot achiv goal organ employe work target nowaday pltd g tarahan inform system assess employe assess process suitabl method chang assess criteria assess process impli asses employe criteria research degre calcul assess simpl addit weight method criteria absente work realiz charact assess co worker system built program hypertext prepocesor php languag base web research result system black box staff ptld g tarahan resolv user need calcul test system manual result base questionnair test admin leader member incom result interv good categori',
            // 'collag student finish studi regist job vacanc inform accord educ major grade point avarag gpa university support career develop graduat inform system convey job inform inform vacanc job inform system university inform access give inform direct make job vacanc inform quick job vacanc inform inform system university lampung develop php program languag gammu sms gateway applic servic system admin insert data inform job vacanc direct sms media inform system filter studi gpa regist stop receiv job vacanc inform sms specif format contact number system develop vacanc inform system latest vacanc inform educ target major gpa',
            // 'six religion indonesia one buddha religion religi day includ buddha religi day buddhist combin solar calendar lunisolar chines calendar peopl memor religi day save file list religi day comput flash disk printout file research research develop android calendar applic convert chines calendar solar calendar show religi day day religi day year applic sqlite databas record religi day',
            // 'sourc islam law major refer foundat islam law decis make sourc islam law al quran hadith ijtihad rise inform technolog develop expect result optim outcom data search inform search pertain hadith effect easier appli daili life exist hadith search system one hadith book search result chronolog order hadith search system author develop hadith inform search optim calcul word input qualiti word input word word obtain develop word search one three shown similar word accord keyword user input sequenc search result last one word examin blackbox test questionnair examin blackbox test proven system valid function questionnair examin specif statement examin result index point respond agre statement comparison examin acquir respond select recent system show recent system good optim',
            // 'islam religi educ import human life achiev peac mental health general islam life guidanc prevent wrongdo power unmatch moral control system aim altern media facilit children teacher parent inform islam learn materi earli childhood elementari school children research e learn applic islam learn built base android java program languag method applic waterfal method design unifi model languag uml applic data islam learn materi children age year facilit user understand learn islam result test data equival partit test show manag rule rule system run function system provid inform addit base test data applic applic user friend variabl categori averag good',
            // 'metro citi one largest citi lampung provinc citi tourist spot visit tourist place metro citi tourist inform introduct tourist attract rapid technolog develop human inform object tourism demand avail inform system geograph inform system base android mobil develop agenc week sport tourism tourism sector metro citi promot potenti region geograph inform system tourist attract base android mobil one import thing citi applic improv imag easi access inform system develop method research xp extrem program applic user map spread attract place metro citi inform place tourist attract equip travel rout applic test techniqu black box test method equival partit result research data user questionnair conclud applic percentag belong categori',
            // 'pkm student creativ program program director research improv qualiti student pkm university lampung exist particip continu increas year inform relat implement pkm university lampung submit bak bureau academ student affair university lampung websit bak student activ visit websit applic provid remind relat pkm inform student obtain pkm inform pkm applic provid remind develop fcm firebas cloud messag sms gateway fcm serv provid android notif pkm applic develop rup ration unifi process phase incept elabor construct transit incept busi model requir elabor phase analysi design implement process perform construct phase test analysi design implement process transit phase test deploy test conduct studi black box test success rate',
            // 'applic program interfac api interfac built system develop entir function system programat access represent state transfer rest one api develop architectur style hypertext transfer protocol http data communic research implement rest develop api back end skincar clinic patient inform system api develop javascript object notat json standard format data communic json web token jwt user authent code research develop api success perform patient administr skin care clinic implement rest make easi develop api structur research produc rest api base back end patient administr inform system skin care clinic api test three stage jwt test multipl backend server api test equival partit system function test',
            // 'develop technolog fast nowaday technolog influenc human life peopl handphon comput necess comput give inform internet internet negat influenc research build web base applic block negat influenc site squid proxi squid daemon proxi server web cach work connect share filter cach web domain block success built creat bandwidth share domain block web base applic',
            // 'insert secret messag steganographi one hide secret messag research compar amelsbr dct method base web applic imag media hide secret messag file format png input cover stegoimag txt data insert result show amelsbr method dct method manipul bright contrast crope',
            // 'administr inform system program kreativita mahasiswa pkm lampung university web base inform system develop assist administr process implement pkm university level record particip pkm university lampung dissemin inform pkm provid refer pkm document university lampung student inform system administr pkm university lampung develop program languag php mysql system develop method ration unifi process rup method rup divid one develop cycl four phase phase rup incept construct elabor transit phase workflow process start busi model requir analysi design implement test final deploy process incept phase busi model requir elabor stage analysi design implement process construct phase test process transit phase deploy softwar product test equival partit test alpha test version test result reach success rate conclud function system rune system',
            // 'technolog make communic limit place time form communic send receiv messag demand strong secur system cryptograph method one secur messag chang form messag difficult understand addit method steganographi hide messag contain media cryptographi steganographi make secur messag studi comparison rsa cryptographi rivest shamir adleman cryptograph transposit column insert contain media amelsbr adapt minimum error signific bit replac steganographi contain media form imag extens jpg input cover imag produc output jpg stegoimag conclus obtain studi ciphertext transposit column faster time insert process produc smaller stegoimag rsa ciphertext rsa ciphertext resist imag cute treatment column ciphertext transposit social media send stegoimag perfect compress process perform',
            // 'studi aim design build field guid applic butterfli speci university lampung base android applic expect mean inform determin classif famili local morpholog butterfli food benefit butterfli university lampung studi waterfal method design unifi model languag uml black box test test function applic data studi consist primari data includ butterfli famili local name butterfli imag caterpillar butterfli descript butterfli speci secondari data includ inform applic famili local name butterfli imag descript butterfli famili genus speci wingspan fli speed food university lampung studi categori famili speci butterfli university lampung result research show equival partit test result show applic butterfli unila compat android os version minimum requir inch inch inch inch resolut base class test applic butterfli unila work proper averag user friend user rate obtain includ categori averag assess interact variabl obtain includ categori result test data base user friend interact variabl applic includ categori good tend good',
            // 'smartphon internet enabl mobil typic person digit assist pda function calendar book address book calcul note smartphon function resembl comput futur smartphon technolog surpass desktop comput technolog term access data internet rapid progress prompt android smartphon manufactur compet make product varieti type specif provid choic consum theorem bay method classifi data basic concept theorem statist calcul probabl white box test test case design method control structur procedur design obtain test case conclus concept probabl produc relat possibl depend probabl',
            // 'script kind import compon cultur javanes javanes script primari script daili major communiti central java human race develop global javanes script slowli set javanes communiti paper elabor convers javanes script imag latin script paper back propag neural network classif method glcm grey level cooccur matrix featur extract method data paper consist javanes vocabulari vocabulari consist syllabl vocabulari write variat two major phase paper data train data test train phase consist crope resiz featur extract nneural network train test phase consist data classif valid check paper matlab softwar neural network tool result research elabor paper highest neural network accuraci averag correl coeffici',
            // 'number cooper indonesia largest world cooper contribut gross domest product mean cooper manag unprofession cooper close govern annual member meet transpar cooper busi process show communic data transpar cooper import cooper difficult show data transpar communic member media support communic develop cooper research succeed develop multi cooper inform system laravel framework multi cooper inform system store member data collect financi transact calcul profit share facilit communic system build base softwar servic saa system cooper subscrib small amount fee ignor develop maintain system system pass test black box test expert judgement',
            // 'sale purchas expect occurr mutual fair transact perfect competit market ideal market structur seller buyer determin price base demand suppli continu doubl auction cda one method occurr transact result buyer cheapest price seller highest price transact made onlin multipl platform promis inform spacious seller buyer access perfect price competit cda method appli onlin market system research make e auction system simul market commod cda method focus indic price chang price fluctuat happen quantiti particip make sale buy affect price fluctuat seller buyer market increas bullish market decreas bearish simul appli profit sell strategi increas price higher random simul',
            // 'kuliah kerja nyata kkn university lampung intracurricular activ involv colleg student lectur field supervisor advanc technolog badan pelaksana kuliah kerja nyata bp kkn necessarili develop android base inform system order give practiculari easi effici access inform purpos research develop kkn inform system base android colleg student field supervisor coordin lectur field supervisor lectur system research develop increment method system develop method base softwar divid three stage requir analysi specif design architectur stage finish vitrif continu stage research produc kkn inform system base android appli smartphon conclus obtain research success build develop inform system help colleg student field advisor lectur perform activ base inform system field case strengthen result questionnair good respond colleg student field supervisor lectur',
            // 'kkn intracurricular activ learn work experi student communiti develop activ implement board real work program bp kkn university lampung made inform system import compon implement kkn bp kkn success carri it duti depend extent kkn inform system manag implement kkn inform system rais risk interfer implement kkn loss materi materi bpkkn bp kkn appli risk manag reduc risk aris problem caus result kkn inform system hamper method nist inform system risk manag framework three stage risk assess risk mitig risk evalu kkn risk interfer sustain inform system final result kkn inform system risk manag form recommend reduc risk occur inform system',
            // 'develop technolog internet exchang data inform problem aris inform confidenti compani institut individu import document data secur data case cryptographi play role encrypt secret messag form unread messag steganographi hide irregular messag imag media result doubl secur deliveri secret messag research develop hybrid system vigener amelsbr cover media form jpg format input cover file type png output stegoimag data insert file text format conclus research hybrid system encrypt secret messag hide cover media make secur data transmiss amelsbr method good steganographi stegoimag withstand process manipul bright contrast imag record domin black color rgb white rgb color variant word imag restor file experienc excess reduct mean manipul bright contrast imag binari imag qualiti grayscal imag',
            // 'numer multipl transact creat difficulti manag obtain inform cooper lack resourc expens make cooper difficult build themself cooper softwar manag financ account inform system saa softwar servic model solut problem saa model user buy app subscrib applic direct applic paper describ develop accout inform system account inform system account featur account rule easier fasilit cooper make financi statement',
            // 'librari integr servic unit unila academ servic student websit qualiti websit evalu base student percept research discuss qualiti measur librari integr servic unit unila websit webqual method purpos research determin variabl webqual affect student satisfact data analysi method structur equat model sem partial squar pls approach result research factor influenc student satisfact inform qualiti usabl',
            // 'paper essay one graduat requir student depart comput scienc faculty mathemat natur scienc lampung paper includ thesi bachelor degre final assign final project vocat degre internship essay student academ rule lampung colleg student make paper guid group lectur type paper made annual depart comput scienc lampung accept student bachelor degre vocat degre activ lectur teach peopl matter imbal ratio number lectur number student comput scienc depart lampung process assign lectur preceptor easi make process assign lectur student easier built decis support system dss simpl addit weight method',
            // 'research develop util present list print matahari integr lampung university siakad develop carri analysi curent present list util develop system aim support user print present list accord complet structur develop file function cpdf librari extens php program languag oracl databas util present list print matahari appli siakad university lampung',
        ];
        
        $vectorizer = new TokenCountVectorizer(new WhitespaceTokenizer());
        
        // Build the dictionary.
        $vectorizer->fit($samples);
        // Transform the provided text samples into a vectorized list.
        $vectorizer->transform($samples);
        return $samples;
    }
    // public function cek()
    // {
    //     $tf = DB::table('tf')->select('tf')->get();
    //     $df = DB::table('df')->select('df')->get();

    //     $sum = collect($tf)->sum('tf');
    //     $sum2 = collect($df)->sum('df');

    //     // return $sum2;
    //     return $sum;
    //     # code...
    // }

}