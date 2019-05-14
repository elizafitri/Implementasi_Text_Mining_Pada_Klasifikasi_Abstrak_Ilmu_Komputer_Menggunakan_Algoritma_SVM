<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Keilmuan extends Model
{
    protected $table = 'keilmuans';
    protected $fillable = [
        'kelas_keilmuan'
    ];
    
    //Foreign key 
    public function klasifikasi()
    {
        return $this->hasMany('App\tokens', 'id_keilmuan');
    }
    //
}
