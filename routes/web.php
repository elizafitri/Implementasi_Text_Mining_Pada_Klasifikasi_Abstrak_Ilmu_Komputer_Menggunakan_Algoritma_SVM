<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// //halaman utama, nanti yang udah di buat search pindah sini ajah
Route::get('/', function () {
    return view('welcome');
});
//ini hanya mencoba menampilkan hasil pencarian 
//Route::get('/coba', 'ResultController@coba');

//ini hanya mencoba
//tujuannya mencari indeks kata yang ada di abstrak (berdasarkan id) hasil tokenisasi 
// Route::get('/resulttoken', 'Admin\PreprosesController@result_token');

//ini mau nyoba 
// Route::get('/savevector', 'Admin\PreprosesController@savevector')->name('savevector');

// ini untuk nampilin hasil pencarian jurnal 
// Route::get('/coba', 'ResultController@cari')->name('coba');

//Route::get('/simpanvec/{id}', 'Admin\PreprosesController@simpanvec')->name('simpanvec');

Auth::routes();



//ini untuk menampilkan halaman home admin
Route::get('/home', 'HomeController@index')->name('home.index');

// ini untuk pencarian jurnal berdasarkan kata kunci yang diinputkan
Route::get('/cari', 'ResultController@search')->name('cari');

// ini untuk pencarian jurnal juga (belum fix)
Route::get('/result', 'ResultController@search')->name('result');

//ini untuk menampilkan jurnal seluruhnya dalam menu Manajemen Jurnal (masih error)
Route::get('/jurnal', 'Admin\PreprosesController@jurnal')->name('jurnal');

//ini untuk menampilkan form tambah data jurnal (masih error)
Route::get('/add', 'Admin\PreprosesController@addForm')->name('addForm');

//action dari form tambah data diatas supaya data baru jurnal tersimpan ke database
Route::post('/prosesadd', 'Admin\PreprosesController@addProses')->name('jurnal.add');

//ini untuk menampilkan form edit data jurnal dengan parameter id
Route::get('/edit/{id}', 'Admin\PreprosesController@editForm')->name('edit');

//action dari form edit data diatas dengan parameter id supaya perubahan data jurnal tersimpan 
Route::post('/edit/update/{id}', 'Admin\PreprosesController@update')->name('jurnal.edit');

//action destroy/hapus data jurnal dengan paremeter id
Route::get('/delete/{id}', 'Admin\PreprosesController@delete')->name('jurnal.delete');

//ini untuk menampilkan form untuk preproses dokumen dengan parameter id
Route::get('/preproses/{id}', 'Admin\PreprosesController@editPre')->name('edit.pre');

//ini untuk menampilkan form untuk preproses dokumen dengan parameter id
Route::get('/preproses/vector/{id}', 'Admin\PreprosesController@vectorPre')->name('vector.pre');

//ini yang bener za!
Route::get('/preproses/preproses/{id}', 'Admin\PreprosesController@preproses')->name('preproses.preproses');

Route::get('/token-urut', 'Admin\PreprosesController@viewTokenUrut')->name('view.token.urut');

Route::post('/save-token-urut', 'Admin\PreprosesController@saveTokenUrut')->name('create.token.urut');

//ini untuk menampilkan array dokumen tempat token ditemukan
Route::get('/arrayvector', 'Admin\PreprosesController@arrayvector');

//action 
Route::post('/savearray', 'Admin\PreprosesController@savearray')->name('savearray');

// ===============================
//action dari form preproses diatas 
Route::post('/hasilpre/{id}', 'Admin\PreprosesController@hasilPre')->name('hasil.pre');


//ini route buat melakukan proses stopword_removal
Route::get('/stoplist', 'Admin\PreprosesController@coba')->name('stoplist');

//ini route untuk proses menyimpan token hasil preproses
Route::post('/createtoken', 'Admin\PreprosesController@createtoken')->name('create.token');

Route::post('/cektoken', 'Admin\PreprosesController@coba')->name('cektoken');

//ini mau nampilin hasil preproses dari proses dokumen
Route::get('/tampilpre', 'Admin\PreprosesController@tampilPre');

//coba snowball library
Route::get('/snow', 'Admin\PreprosesController@cobasnow')->name('cobasnow');

Route::get('/bobot-index/{id}', 'Admin\PreprosesController@bobot')->name('bobot');
Route::post('/bobot-index/save', 'Admin\PreprosesController@saveBobot')->name('bobot.save');

Route::get('/view-df', 'Admin\PreprosesController@viewDf')->name('view.df');
Route::post('/save-df', 'Admin\PreprosesController@saveDf')->name('save.df');

Route::get('/view-idf', 'Admin\PreprosesController@idf')->name('view.idf');



