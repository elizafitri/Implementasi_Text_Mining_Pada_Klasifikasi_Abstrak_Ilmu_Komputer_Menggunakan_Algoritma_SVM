<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class idf extends Model
{
    protected $table = 'idf';
    protected $fillable = [
        'id_term', 'idf'
    ];
    
    //Foreign key 
    public function token ()
    {
        return $this->hasMany('App\tokens', 'id');
    }
}
