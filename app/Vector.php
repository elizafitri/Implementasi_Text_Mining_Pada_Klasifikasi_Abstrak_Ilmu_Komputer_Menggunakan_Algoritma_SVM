<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Vector extends Model
{
    protected $table = 'table_vectors';
    protected $fillable = [
        'id_term','vector_doc','total_vector'
    ];

    
    public function token ()
    {
        return $this->belongsTo('App\Token', 'id_term');
    }
}
