<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    protected $primaryKey = 'id_service';
    use HasFactory;
    protected $table = 'service';
    public $timestamps = false;
    protected $fillable = [

        'nom',
        'description',
        'image',
        'type'

    ];
}
