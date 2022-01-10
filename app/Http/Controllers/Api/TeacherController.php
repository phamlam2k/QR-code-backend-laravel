<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\TeacherService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class TeacherController extends Controller
{
    private $teacherService;
    public function __construct(TeacherService $teacherService)
    {
        $this->teacherService = $teacherService;
    }

    public function index(Request $request)
    {
        $keyword = $request->keyword;
        $limit = $request->limit;
        $page = $request->page;

        try {
                $teacher = $this->teacherService->getAll($page, $limit, $keyword);

                return response()->json([
                    'status'    => 1,
                    'code'      => Response::HTTP_OK,
                    'data'     => $teacher
                ]);
        }catch (\Exception $err){
            return response()->json([
                'status'    => 0,
                'code'      => Response::HTTP_INTERNAL_SERVER_ERROR,
                'message'   => $err->getMessage(),
            ]);
        }
    }

    public function destroy($id)
    {
        try{
            DB::table("subject")
                ->where("id_teacher", $id)
                ->delete();

            $result = DB::table("teachers")
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
        }catch(\Exception $err){
            return response()->json([
                'status'    => 0,
                'code'      => Response::HTTP_NOT_FOUND,
                'message'   => "Somthing went wrong"
            ]);
        }
    }

    public function show($id){
        $result_id = DB::table('teachers')->find($id);

        return response()->json([
            'status'    => 1,
            'code'      => Response::HTTP_OK,
            'data'      => $result_id,
        ]);
    }

    public function store(Request $request)
    {
        try{
            $id_teacher = $request->id_teacher;
            $full_name = $request->name;
            $email = $request->email;
            $phonenumber = $request->phonenumber;
            $photo = $request->photo;

            $result_id = DB::table('teachers')->where('id_teacher', $id_teacher)->get();
            $result_phonenumber = DB::table('teachers')->where('phonenumber', $phonenumber)->get();
            $result_email = DB::table('teachers')->where('email', $email)->get();

            if(count($result_id) == 0 && count($result_phonenumber) == 0 && count($result_email) == 0){
                DB::insert('insert into teachers (id_teacher, name, email, phonenumber,  photo ) values (?, ?, ?, ?, ?)', [$id_teacher, $full_name, $email, $phonenumber, $photo]);
                DB::insert('insert into users (id_student, name, photo, email, password, admin) values (?, ?, ?, ?, ?, ?)', [$id_teacher, $full_name, $photo, $email, "12345678", 1]);

                return response()->json([
                    'status'    => 1,
                    'code'      => Response::HTTP_OK,
                    'message'   => "Create teacher success",
                ]);
            }

            if(count($result_phonenumber) > 0){
                return response()->json([
                    'status'    => 1,
                    'code'      => Response::HTTP_IM_USED,
                    'message'   => "This phone number is used",
                ]);
            }

            if(count($result_id) > 0){
                return response()->json([
                    'status'    => 1,
                    'code'      => Response::HTTP_IM_USED,
                    'message'   => "This ID is used",
                ]);
            }

            if(count($result_email) > 0){
                return response()->json([
                    'status'    => 1,
                    'code'      => Response::HTTP_IM_USED,
                    'message'   => "This email is used",
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

    public function update(Request $request, $id)
    {
        $photo = $request->photo;
        $id_teacher = $request->id_teacher;
        $full_name = $request->name;
        $email = $request->email;
        $phonenumber = $request->phonenumber;

        $result = DB::update('update teachers set id_teacher = ?, name = ? ,email = ?, phonenumber = ?, photo = ? where id = ?', [$id_teacher, $full_name,$email, $phonenumber, $photo, $id]);

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
