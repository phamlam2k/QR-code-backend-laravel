<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\SubjectService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class SubjectController extends Controller
{
    private $subjectService;

    public function __construct(SubjectService $subjectService)
    {
        $this->subjectService = $subjectService;
    }

    public function index(Request $request)
    {
        try {
            $keyword = $request->keyword;
            $page = $request->page;
            $limit = $request->limit;
            $name = $request->name;

            $id = DB::table('teachers')->where('name', 'LIKE', $name)->first();

            if(isset($id)){
                $id_user = $id->id;
                $subject = $this->subjectService->getTeacher($page, $limit, $keyword, $id_user);

                return response()->json([
                    'status'    => 1,
                    'code'      => Response::HTTP_OK,
                    'data'     => $subject
                ]);
            }else{
                $subject = $this->subjectService->getAll($page, $limit, $keyword);

                return response()->json([
                    'status'    => 1,
                    'code'      => Response::HTTP_OK,
                    'data'     => $subject
                ]);
            }


        }catch (\Exception $err){
            return response()->json([
                'status'    => 0,
                'code'      => Response::HTTP_INTERNAL_SERVER_ERROR,
                'message'   => $err->getMessage(),
            ]);
        }
    }

    public function store(Request $request)
    {
        try{
            $id_subject = $request->id_subject;
            $name_subject = $request->name;
            $number_lesson = $request->number_lesson;
            $id_teacher = $request->id_teacher;
            $qr_code = $request->name;

            DB::insert('insert into subject (id_subject, name, number_lesson, id_teacher, qrcode, status ) values (?, ?, ?, ?, ?, ?)', [$id_subject, $name_subject, $number_lesson, $id_teacher, $qr_code, 0]);

            return response()->json([
                'status'    => 1,
                'code'      => Response::HTTP_OK,
                'message'   => "Create subject success",
            ]);
        }catch (\Exception $err){
            return response()->json([
                'status'    => 0,
                'code'      => Response::HTTP_INTERNAL_SERVER_ERROR,
                'message'   => $err->getMessage(),
            ]);
        }
    }

    public function show($id){

        $subject = $this->subjectService->getDetailSubject($id);

        return response()->json([
            'status'    => 1,
            'code'      => Response::HTTP_OK,
            'data'      => $subject,
        ]);
    }

    public function destroy($id)
    {
        DB::table("table_register")
            ->where("id_subject", $id)
            ->delete();

        $result = DB::table("subject")
            ->where("id", $id)
            ->delete();

        if($result){
            return response()->json([
                'status'    => 1,
                'code'      => Response::HTTP_OK,
                'message'   => "Delete success"
            ]);
        }else{
            return response()->json([
                'status'    => 0,
                'code'      => Response::HTTP_NOT_FOUND,
                'message'   => "Somthing went wrong"
            ]);
        }
    }

    public function update(Request $request, $id)
    {
        $status = $request->status;
        $id_subject = $request->id_subject;
        $name_subject = $request->name;
        $number_lesson = $request->number_lesson;
        $id_teacher = $request->id_teacher;

        if(isset($status)){
            $result = DB::update('update subject set status = ? where id = ?', [$status , $id]);
        }else{
            $result = DB::update('update subject set id_subject = ?, name = ? ,number_lesson = ?,  id_teacher = ?, qrcode = ? where id = ?', [$id_subject, $name_subject,$number_lesson, $id_teacher, $name_subject, $id]);
        }

        if($result){
            return response()->json([
                'status'    => 1,
                'code'      => Response::HTTP_OK,
                'message'   => "Update success"
            ]);
        }else{
            return response()->json([
                'status'    => 0,
                'code'      => Response::HTTP_NOT_FOUND,
                'message'   => "Somthing went wrong"
            ]);
        }
    }
}
