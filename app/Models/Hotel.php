<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Hotel extends Model
{
    use HasFactory;
    protected $table = 'hotel';
    public $timestamps = false;


    protected $fillable = [
        'id',
        'nom',
        'localisation',
        'prix',
        'étoiles',
        'image',

    ];
}
