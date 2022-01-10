<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use \App\Services\TableRegister;
use Symfony\Component\HttpFoundation\Response;

class TableRegisterController extends Controller
{
    private $tableRegister;
    public function __construct(TableRegister $tableRegister)
    {
        $this->tableRegister = $tableRegister;
    }

    public function index(Request $request)
    {
        $keyword = $request->keyword;
        $page = $request->page;
        $limit = $request->limit;

        try{
            $table = $this->tableRegister->getAll($keyword, $page, $limit);

            return response()->json([
                'status'    => 1,
                'code'      => Response::HTTP_OK,
                'data'      => $table
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
        $result = DB::table("table_register")
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

    public function show($id)
    {
        $table_register = $this->tableRegister->getDetailTableRegister($id);

        return response()->json([
            'status'    => 1,
            'code'      => Response::HTTP_OK,
            'data'      => $table_register,
        ]);
    }

    public function store(Request $request)
    {
        try{
            $student = $request->student;
            $start_date = $request->start_date;
            $end_date = $request->end_date;
            $subject = $request->id_subject;

            for($i = 0; $i < count($student); $i ++){
                DB::insert('insert into table_register (id_student, id_subject, start_date, end_date ) values (?, ?, ?, ? )', [$student[$i], $subject, $start_date, $end_date]);
            }

            return response()->json([
                'status'    => 1,
                'code'      => Response::HTTP_OK,
                'message'   => "Add subject and student success",
            ]);
        }catch (\Exception $err){
            return response()->json([
                'status'    => 0,
                'code'      => Response::HTTP_INTERNAL_SERVER_ERROR,
                'message'   => $err->getMessage(),
            ]);
        }
    }
}
