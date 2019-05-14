<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Stopword extends Model
{
    protected $table = 'stopwords';
    protected $fillable = [
        'stoplist'
    ];
}
