<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class Car extends Model
{
    protected $table = 'cars';

    // Relación
    public function user(){
    	return $this->belongsTo('App\Models\User', 'user_id');
    }  
}
