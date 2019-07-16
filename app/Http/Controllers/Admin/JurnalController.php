<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Klasifikasi;
use App\Stopword;
use App\Keilmuan;

class JurnalController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */

    //method ini untuk agar halaman ini harus login dulu
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */

    //halaman daftar jurnal
    public function index()
    {
        //model dari Klasifikasi.php untuk mengambil data dari dara klasifikasis
        $jurnal = Klasifikasi::orderBy('id', 'desc')->get();

        //menampilkan view admin adalah foldernya di view index itu nama filenya
        return view('admin.index')->with([
             'jurnal' => $jurnal,
         ]);
    }

    //method untuk menampilkan form tambah data
    public function addForm()
    {
        //model dari Keilmuan.php untuk mengambil data dari dara keilmuans
        //karena kan kita mau ambil id topiknya jadi di tampilin deh
        $keilmuan = Keilmuan::orderBy('id', 'desc')->get();

        //ini juga nampilin view addnya
        return view('admin.add')->with([
            'keilmuan' => $keilmuan
        ]);
    }

    //nah method untuk proses aksi menambahkan datanya, variable terserah mau diisi apa
    //jangan lupa cek di route pasti mudeng deh
    //dan ini proses addnya, yang di taro di tag form action
    public function store( Request $p)
    {
        //ini validasi kalo isian itu ga boleh null, kita juga bisa membatasi karakternya dari sini
        $this->validate($p, [
            'id_keilmuan' => 'required',
            'judul_jurnal' => 'required',
            'nama_penulis' => 'required',
            'abstrak' => 'required',
            'kata_kunci' => 'required'
        ]);
        
        //ini prosesnya
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

    //menampilkan edit form dengan parameter id, paramater id ini
    //ngambilnya di route /edit/{id}, untuk mendapakan idnya
    // di herf di kasih parameter di button edit 
    public function editForm($id)
    {
        $jurnal = Klasifikasi::where('id', $id)->first();
        $keilmuan = Keilmuan::orderBy('id', 'desc')->get();
        return view('admin.edit')->with([
            'jurnal' => $jurnal,
            'keilmuan' => $keilmuan
          ]);
    }

    //dan ini proses editnya, yang di taro di tag form action
    public function update(Request $r, $id)
    {
        $this->validate($r, [
            'id_keilmuan' => 'required',
            'judul_jurnal' => 'required',
            'nama_penulis' => 'required',
            'abstrak' => 'required',
            'kata_kunci' => 'required'
        ]);

        $jurnal= Klasifikasi::find($id);
        $jurnal->id_keilmuan = $r->id_keilmuan;
        $jurnal->judul_jurnal = $r->judul_jurnal;
        $jurnal->nama_penulis = $r->nama_penulis;
        $jurnal->abstrak = $r->abstrak;
        $jurnal->kata_kunci = $r->kata_kunci;
        $jurnal->save();
        return redirect()->route('jurnal');
    }

    //paling gampang proses delete
    public function delete($id)
    {
        $jurnal = Klasifikasi::find($id);
        $jurnal->delete();
        return redirect()->route('jurnal');
    }

    public function hasil($id)
    {
        $jurnal = Klasifikasi::where('id', $id)->first();
        $kata = $jurnal->abstrak;
        $abstrak = $this->tanda_baca($kata);
        $low = strtolower($abstrak);
        $token = explode(' ', $low);
        $hasil_array_token = print_r($token, TRUE);
        // $stoplist = $this->stoplist($hasil_array_token);
        $total_token = count ($token);
        $stoplist = implode(' ', $low);
        $stopword_removal = print_r($stoplist, TRUE);

        return view('admin.preproses')->with([
            'jurnal' => $jurnal,
            'low' => $low,
            'token' => $token,
            'hasil_array_token' => $hasil_array_token,
            'total_token' => $total_token,
            'stoplist' => $stoplist
        ]);
    }

    public function tanda_baca($teks)
    {
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
        $teks = rtrim($teks); 
        // $teks = str_replace(" ", " ", $teks);
        
        // //terapkan stop word removal
        $astoplist = array ("i", "me", "my", "myself", "we", "us", "our", "ours", "ourselves", "you", "your", "yours",
                            "yourself", "yourselves", "he", "him", "his", "himself", "she", "her", "hers", "herself", 
                            "it", "its", "itself", "they", "them", "their", "theirs", "themselves", "what", "which", 
                            "who", "whom", "this", "that", "these", "those", "am", "is", "are", "was", "were", "be", "been", 
                            "being", "have", "has", "had", "having", "does", "did", "doing", "will", "would", "shall", 
                            "should", "can", "could", "may", "might", "must", "ought", "i'm", "you're", "he's", "she's", 
                            "it's", "we're", "they're", "i've", "you've", "we've", "they've", "i'd", "you'd", "he'd", "she'd", 
                            "we'd", "they'd", "i'll", "you'll", "he'll", "she'll", "we'll", "they'll", "isn't", "aren't", 
                            "wasn't", "weren't", "hasn't", "haven't", "hadn't", "doesn't", "don't", "didn't", "won't", 
                            "wouldn't", "shan't", "shouldn't", "can't", "cannot", "couldn't", "mustn't", "let's", "that's", 
                            "who's", "what's", "here's", "there's", "when's", "where's", "why's", "how's", "daren't", 
                            "needn't", "oughtn't", "mightn't", "a", "an", "the", "and", "but", "if", "or", "because", "as", 
                            "until", "while", "of", "at", "by", "for", "with", "about", "against", "between", "into", 
                            "through", "during", "before", "after", "above", "below", "to", "from", "up", "down", "in", 
                            "out", "on", "off", "over", "under", "again", "further", "then", "once", "here", "there", 
                            "when", "where", "why", "how", "all", "any", "both", "each", "few", "more", "most", "other", 
                            "some", "such", "no", "nor", "not", "only", "own", "same", "so", "than", "too", "very", "one", 
                            "every", "least", "less", "many", "now","ever", "never", "say", "says", "said", "also", "get", 
                            "go", "goes", "just", "made", "make", "put", "see", "seen", "whether", "like", "well", "back",
                            "even","still","way","take","since","another","however","two","three", "four","five","first",
                            "second","new","old","high","long");
          
            // foreach ($astoplist as $i => $value) {    
            //         $teks = str_replace($astoplist[$i], "", $teks); 
            // }

            return $teks;
    }


    // jangan lupa kalo mau ambil data dari database itu idupin xampp :D
    // dan jangan lupa paling atas use App\NamaModel

    // oh iya itu proses pencarian aku pake plugin bawaan dari footable, 
    // jadi ga perlu bikin fungsi lagi biar ga ribet hehehe
}
