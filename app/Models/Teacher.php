<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Teacher extends Model
{
    use HasFactory;

    protected $table= "teachers";
    protected $fillable = ['id', 'id_teacher', 'name', 'email', 'phonenumber', 'photo'];
}
