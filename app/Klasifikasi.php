<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Klasifikasi extends Model
{
    protected $table = 'klasifikasis';
    protected $fillable = [
        'id_keilmuan','judul_jurnal', 'nama_penulis', 'abstrak', 'kata_kunci'
    ];
  
    public function keilmuan ()
    {
        return $this->belongsTo('App\Keilmuan', 'id_keilmuan');
    }

    public function tf()
    {
        return $this->hasMany('App\tf');
    }
}
