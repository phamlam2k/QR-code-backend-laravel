<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;

class TeacherService
{
    public function getAll($page, $limit, $keyword)
    {
        return DB::table("teachers")
            ->where('id_teacher', 'LIKE', "%{$keyword}%")
            ->orWhere('name', 'LIKE', "%{$keyword}%")
            ->offset(($page - 1) *10)
            ->paginate($limit);
    }
}
