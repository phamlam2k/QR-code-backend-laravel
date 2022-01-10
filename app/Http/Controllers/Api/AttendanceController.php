<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\AttendanceService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class AttendanceController extends Controller
{
    private $attendanceService;
    public function __construct(AttendanceService $attendanceService)
    {
        $this->attendanceService = $attendanceService;
    }

    public function index(Request $request)
    {
        try{
                $table = $this->attendanceService->get($request);

                return response()->json([
                    'status' => 1,
                    'code'   => Response::HTTP_OK,
                    'data'  => $table
                ]);
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
            $student = $request->id_student;
            $subject = $request->subject;
            $time = $request->time;
            $date = $request->date;

            $id_student = DB::table('student')->where("id_student","LIKE" ,$student)->first()->id;
            $id_subject = DB::table('subject')->where("name","LIKE" ,$subject)->first()->id;
            $status = DB::table('subject')->where("name","LIKE" ,$subject)->first()->status;
            $subject_id_same= DB::table('attendance')
                ->where('id_subject', 'LIKE', $id_subject)
                ->where('id_student', 'LIKE', $id_student)
                ->where('date', 'LIKE', $date)
                ->get();

            if($status == 1){
                if(count($subject_id_same) == 0){
                    if(isset($id_student) && isset($id_subject)){
                        DB::insert('insert into attendance (id_student, time, id_subject, date) values (?, ?, ?, ?)', [$id_student, $time, $id_subject, $date]);

                        return response()->json([
                            'status'    => 0,
                            'code'      => Response::HTTP_OK,
                            'message'   => "Attend success",
                        ]);
                    }else{
                        return response()->json([
                            'status'    => 0,
                            'code'      => Response::HTTP_NOT_FOUND,
                            'message'   => "Attend fail",
                        ]);
                    }
                }else{
                    return response()->json([
                        'status'    => 0,
                        'code'      => Response::HTTP_NOT_FOUND,
                        'message'   => "You had attended",
                    ]);
                }
            }else{
                return response()->json([
                    'status'    => 0,
                    'code'      => Response::HTTP_NOT_FOUND,
                    'message'   => "Qr code had been closed",
                ]);
            }
        }catch(\Exception $err){
            return response()->json([
                'status'    => 0,
                'code'      => Response::HTTP_INTERNAL_SERVER_ERROR,
                'message'   => $err->getMessage(),
            ]);
        }
    }
}
