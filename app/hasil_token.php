<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class hasil_token extends Model
{
    protected $table = 'hasil_tokens';
    protected $fillable = [
        'token'
    ];

    public function Table_vector()
    {
        return $this->hasMany('App\table_vector', 'id_term');
    }
}
