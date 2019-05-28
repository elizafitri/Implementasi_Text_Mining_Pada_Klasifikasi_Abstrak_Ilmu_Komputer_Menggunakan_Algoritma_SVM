<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class tf extends Model
{
    protected $table = 'tf';
    protected $fillable = [
        'id_term','id_doc', 'indeks', 'tf'
    ];
  
    public function token ()
    {
        return $this->belongsTo('App\Token', 'id_token');
    }

    public function klasifikasi ()
    {
        return $this->belongsTo('App\Klasifikasi', 'id');
    }
}
