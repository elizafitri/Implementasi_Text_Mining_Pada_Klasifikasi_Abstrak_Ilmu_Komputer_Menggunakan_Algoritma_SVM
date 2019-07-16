<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class tfidf extends Model
{
    protected $table = 'tfidfs';
    protected $fillable = [
        'id_term', 'tfidf'
    ];
    
    //Foreign key 
    public function token ()
    {
        return $this->hasMany('App\tokens', 'id');
    }
}
