<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    protected $table = "student";
    protected $fillable = ['id', 'id_student', 'name', 'email', 'phonenumber', 'major', 'birth', 'photo'];

    public function table_register()
    {

    }
}
