<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    // Table name
    protected $table = 'posts';
    // Primary key
    public $primaryKey = 'id';
    // Timestamps - juz dodalismy, ale mozemy tutaj wylaczyc dajac false
    public $timestamps = true;

    //wyswietlanie postów uzytkownika na dashboard
    //jeszcze funkcja w modelu User.php
    public function user() {
        //relacja (one to many) zwrotna do post do users
        return $this->belongsTo('App\User');
    }
}   
