<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class vector_token extends Model
{
    protected $table = 'vector_tokens';
    protected $fillable = [
        'id', 'id_token', 'vector_doc', 'df'
    ];

}
