<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Table_vector extends Model
{
    protected $table = 'table_vectors';
    protected $fillable = [
        'id_vector','id_term', 'vector_doc', 'total_vector'
    ];

    public function hasil_token()
    {
        return $this->belongsTo('App\hasil_token', 'id_term');
    }
}
