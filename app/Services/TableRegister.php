<?php

namespace App\Services;

use App\Models\TableRegister as TableRegisterModel;
use Illuminate\Database\Query\Builder;

class TableRegister
{
    public function getAll($keyword, $page, $limit)
    {
        $tableRegister = TableRegisterModel::with(['student', 'subject'])
            ->whereIn('id_subject', function (Builder $q) use ($keyword) {
                $q->select('id')
                    ->from('subject')
                    ->where('name', 'like',"%{$keyword}%");
            })
            ->offset(($page - 1) *10)
            ->paginate($limit);
        for ($i = 0; $i < count($tableRegister); $i ++){
            $tableRegister[$i]['student'] = $tableRegister[$i]->student;
            $tableRegister[$i]['subject'] = $tableRegister[$i]->subject;
            $tableRegister[$i]['subject']['teacher'] = $tableRegister[$i]->subject->teacher;
            unset($tableRegister[$i]['subject']['id_teacher']);
            unset($tableRegister[$i]['id_student']);
            unset($tableRegister[$i]['id_subject']);
        }

        return $tableRegister;
    }

    public function getDetailTableRegister($id)
    {
        $tableRegister = TableRegisterModel::with(['student', 'subject'])->get();
        for ($i = 0; $i < count($tableRegister); $i ++){
            $tableRegister[$i]['student'] = $tableRegister[$i]->student;
            $tableRegister[$i]['subject'] = $tableRegister[$i]->subject;
            $tableRegister[$i]['subject']['teacher'] = $tableRegister[$i]->subject->teacher;
            unset($tableRegister[$i]['subject']['id_teacher']);
            unset($tableRegister[$i]['id_student']);
            unset($tableRegister[$i]['id_subject']);
        }

        return $tableRegister->find($id);
    }
}
