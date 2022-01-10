<?php

namespace App\Services;
use http\Env\Request;
use Illuminate\Support\Facades\DB;

class StudentService
{
    public function getAll($keyword, $limit, $page)
    {
        return DB::table('student')
        ->where('id_student', 'LIKE', "%{$keyword}%")
        ->orWhere('name', 'LIKE', "%{$keyword}%")->offset(($page - 1)*10)->paginate($limit);
    }

    public function delete($id)
    {
        DB::table("student")
            ->where("id", $id)
            ->delete();
    }




}
