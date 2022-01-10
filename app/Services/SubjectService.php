<?php

namespace App\Services;
use App\Models\Subject;
use http\Env\Request;

class SubjectService
{

    public function getAll($page, $limit, $keyword)
    {
        $subject = Subject::with(['teacher'])
            ->where('name', 'LIKE', "%{$keyword}%")
            ->orWhere('id_subject', 'LIKE', "%{$keyword}%")
            ->offset(($page - 1)*10)
            ->paginate($limit);
        for ($i = 0; $i < count($subject); $i ++){
            $subject[$i]['teacher'] = $subject[$i]->teacher;
            unset($subject[$i]['id_teacher']);
        }
        return $subject;
    }

    public function getTeacher($page, $limit, $keyword, $id_user){
        $subject = Subject::with(['teacher'])
            ->where('id_teacher', 'LIKE', $id_user)
            ->where('name', 'LIKE', "%{$keyword}%")
            ->where('id_subject', 'LIKE', "%{$keyword}%")
            ->offset(($page - 1)*10)
            ->paginate($limit);
        for ($i = 0; $i < count($subject); $i ++){
            $subject[$i]['teacher'] = $subject[$i]->teacher;
            unset($subject[$i]['id_teacher']);
        }
        return $subject;
    }

    public function getDetailSubject($id)
    {
        $subject = Subject::all();
        for ($i = 0; $i < count($subject); $i ++){
            $subject[$i]['teacher'] = $subject[$i]->teacher;
            unset($subject[$i]['id_teacher']);
        }

        return $subject->find($id);
    }
}
