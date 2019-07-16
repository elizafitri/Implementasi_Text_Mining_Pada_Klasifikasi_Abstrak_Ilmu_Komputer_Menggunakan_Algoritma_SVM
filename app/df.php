<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class df extends Model
{
    protected $table = 'df';
    protected $fillable = [
        'id_term', 'df'
    ];
    
    // Foreign key 
    // public function token ()
    // {
    //     return $this->hasMany('App\tokens', 'id_token');
    // }
    public function tf ()
    {
        return $this->belongsTo('App\tf', 'id_term');
    }
}
