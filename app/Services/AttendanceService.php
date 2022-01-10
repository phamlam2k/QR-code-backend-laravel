<?php

namespace App\Services;

use App\Models\Attendance;
use Illuminate\Database\Query\Builder;

class AttendanceService
{
    public function get($resquest){
        $student_id = $resquest->student_id;
        $subject_id = $resquest->subject_id;
        $page = $resquest->page;
        $limit = $resquest->limit;

        if($subject_id == '' && $student_id == ''){
            $tableRegister = Attendance::with(['student', 'subject'])
                ->offset(($page - 1) *10)
                ->paginate($limit);
        }elseif($subject_id == '' || $student_id == ''){
            $tableRegister = Attendance::with(['student', 'subject'])
                ->where('id_student', $student_id)
                ->orWhere('id_subject', $subject_id)
                ->offset(($page - 1) *10)
                ->paginate($limit);
        }else{
            $tableRegister = Attendance::with(['student', 'subject'])
                ->where('id_student', $student_id)
                ->where('id_subject', $subject_id)
                ->offset(($page - 1) *10)
                ->paginate($limit);
        }

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
}
