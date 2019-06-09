<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Klasifikasi;
use App\Keilmuan;
use App\Stopword;
use App\Token;
use App\hasil_token;
use App\table_vector;
use markfullmer\porter2\Porter2;
// use Phpml\FeatureExtraction\TfIdfTransformer;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Input;
use DB;
use App\vector;
use App\tf;

define('WORD_BOUNDARY', "[^a-zA-Z']+");

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
            'id' => 'required',
            'id_keilmuan' => 'required',
            'judul_jurnal' => 'required',
            'nama_penulis' => 'required',
            'abstrak' => 'required',
            'kata_kunci' => 'required'
        ]);

         //prosesnya disini
        $jurnal= Klasifikasi::find($id);
        //$jurnal->id = $r->id;
        $jurnal->id_keilmuan = $r->id_keilmuan;
        $jurnal->judul_jurnal = $r->judul_jurnal;
        $jurnal->nama_penulis = $r->nama_penulis;
        $jurnal->abstrak = $r->abstrak;
        $jurnal->kata_kunci = $r->kata_kunci;
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
        $stem = $this->porterstemmer_process($low);
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
            $cek = DB::table('tokens')->select('token')->where('token', $token_array[$i])->count();
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
            $teks = trim(preg_replace('/\s\s+/', ' ', str_replace("\n", " ", $teks)));
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

        $df_array = [];
        foreach ($id_term as $key => $id_term) {
            $df_array[] = [
                'id_term' => $input['id_term'][$key],
                'df' => $input['df'][$key]
            ];
        }

        // dd($tf_array);
        tf::insert($tf_array);
        // df::insert($df_array);
        return back()->withInput();
        // $tf = \App\tf::create([
        //     'id_term' => request()->get('id_term'),
        //     'id_doc' => request()->get('id_doc'),
        //     'indeks' => request()->get('indeks'),
        //     'tf' => request()->get('tf')
        // ]);

        // $df = \App\df::create([
        //     'id_term' => request()->get('id_term'),
        //     'df' => request()->get('df')
        // ]);

        // return redirect()->route('');
    }

    public function bobot($id)
    {
        $token = Token::all();
        $abstrak = Klasifikasi::find($id);
        $preproses = $abstrak->preproses;
        $tokenisasi = explode(" ", $preproses);
        // idf(term) = log(D/df) dimana df adl jumlah dokumen yang mengandung suatu term
        // tfidf = tf x idf

        return view('admin.vectordoc')->with([
            'abstrak' => $abstrak,
            'tokenisasi' => $tokenisasi,
            'token' => $token
        ]);

    }

    public function viewDF()
    {
        $token = Token::all();

        $break = 0;
        foreach ($token as $key => $value) {
            // if ($break == 100) break;{
                // echo $value->id.'<br>';
                $tf = $value->id;
                $df = tf::where('id_term', $tf)->get();

                echo $df->count().'-'.$tf.'<br>';
            // }

            // $break++;
        }
    }
}