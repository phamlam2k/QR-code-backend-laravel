<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Services\StudentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class StudentController extends Controller
{
    private $studentService;
    public function __construct(StudentService $studentService)
    {
        $this->studentService = $studentService;
    }

    public function index(Request $request)
    {
        $keyword = $request->keyword;
        $limit = $request->limit;
        $page = $request->page;

        try {
            $student = $this->studentService->getAll($keyword, $limit, $page);

            return response()->json([
                'status'    => 1,
                'code'      => Response::HTTP_OK,
                'data'     => $student
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
        $result = DB::table("student")
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
                'message'   => "Something went wrong"
            ]);
        }
    }

    public function store(Request $request)
    {
        try{
            $id_student = $request->id_student;
            $full_name = $request->name;
            $email = $request->email;
            $phonenumber = $request->phonenumber;
            $major = $request->major;
            $birth = $request->birth;
            $photo = $request->photo;

            $result_id = DB::table('student')->where('id_student', $id_student)->get();
            $result_phonenumber = DB::table('student')->where('phonenumber', $phonenumber)->get();
            $result_email = DB::table('student')->where('email', $email)->get();

            if(count($result_id) == 0 && count($result_phonenumber) == 0 && count($result_email) == 0){
                DB::insert('insert into student (id_student, name, email, phonenumber, major, birth, photo ) values (?, ?, ?, ?, ?, ?, ?)', [$id_student, $full_name, $email, $phonenumber, $major, $birth, $photo]);
                DB::insert('insert into users (id_student, name, photo,  email, password, admin) values (?, ?, ?, ?, ?, ?)', [$id_student, $full_name, $photo, $email, "12345678", 2]);

                return response()->json([
                    'status'    => 1,
                    'code'      => Response::HTTP_OK,
                    'message'   => "Create student success",
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

    public function show($id){
        $result_id = DB::table('student')->find($id);

        return response()->json([
            'status'    => 1,
            'code'      => Response::HTTP_OK,
            'data'      => $result_id,
        ]);
    }

    public function update(Request $request, $id)
    {
        $photo = $request->photo;
        $id_student = $request->id_student;
        $full_name = $request->name;
        $email = $request->email;
        $phonenumber = $request->phonenumber;
        $major = $request->major;
        $birth = $request->birth;

        $result = DB::update('update student set id_student = ?, name = ? ,email = ?, phonenumber = ?, major = ?, birth = ?, photo = ? where id = ?', [$id_student, $full_name,$email, $phonenumber, $major, $birth, $photo, $id]);

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

    public function info(Request $request)
    {
        try{
            $id_student = $request->id_student;

            $result = DB::table('student')->where("id_student", "LIKE", $id_student)->first();

            return response()->json([
                'status' => 1,
                'code' => Response::HTTP_OK,
                'data' => $result

            ]);
        }catch(\Exception $err){
            return response()->json([
                'status' => 0,
                'code' => Response::HTTP_NOT_FOUND,
                'message' => $err->getMessage()
            ]);
        }
    }
}
