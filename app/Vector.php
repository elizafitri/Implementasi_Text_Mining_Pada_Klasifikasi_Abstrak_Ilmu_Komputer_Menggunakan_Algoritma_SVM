<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class vector extends Model
{
    protected $table = 'vectors';
    protected $fillable = [
        'id_term','id_doc','vector_doc','tf'
    ];

    public function hasil_token()
    {
        return $this->belongsTo('App\hasil_token', 'id_term');
    }
}
