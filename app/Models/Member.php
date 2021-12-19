<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Member extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'nama',
        'email',
        'alamat',
        'avatar',
        'created_at',
        'updated_at',
        'uuid'
    ];

    protected $hidden = [
        'id'
    ];
}
