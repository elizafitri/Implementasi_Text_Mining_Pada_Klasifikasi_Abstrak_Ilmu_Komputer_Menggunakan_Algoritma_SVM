<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Token extends Model
{
    protected $table = 'new_tokens';
    protected $fillable = [
        'token'
    ];

    public function tf()
    {
        return $this->hasMany('App\tf');
    }
}
