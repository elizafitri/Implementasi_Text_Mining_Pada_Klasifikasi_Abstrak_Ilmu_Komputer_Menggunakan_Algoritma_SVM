<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class tfidf extends Model
{
    protected $table = 'tfidfs';
    protected $fillable = [
        'id_doc','vector_term'
    ];
  
    public function keilmuan ()
    {
        return $this->belongsTo('App\Klasifikasi', 'id_doc');
    }
}
