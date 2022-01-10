<?php

namespace App\Services;

use App\Models\Lists;
use Illuminate\Support\Facades\DB;

class ListService {
    public function getAll(){
        return Lists::all();
    }

    public function getStudent($id){
        $list = Lists::all();

        return $list->where("user_id", "LIKE", $id);
    }
}
