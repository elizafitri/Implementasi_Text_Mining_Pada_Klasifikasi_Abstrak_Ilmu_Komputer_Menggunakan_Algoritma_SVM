<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Klasifikasi;
use App\Keilmuan;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */

    //fungsi untuk memanggil data dari tabel klasifikasis dan tabel keilmuan
    //$jumlah dan $kategori dipanggil ke home.blade (dengan return view ('home'))
    public function index()
    {
        $jumlah = Klasifikasi::count();
        $kategori = Keilmuan::count();
        
        return view('home')->with([
            'jumlah' => $jumlah,
            'kategori' => $kategori
        ]);
    }

    
}
