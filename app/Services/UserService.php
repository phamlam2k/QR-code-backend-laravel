<?php

namespace App\Services;
use Illuminate\Support\Facades\DB;

class UserService
{
    public function getAll($page,$limit)
    {
        return DB::table("users")
            ->offset(($page - 1) * 10)
            ->paginate($limit);
    }
}
