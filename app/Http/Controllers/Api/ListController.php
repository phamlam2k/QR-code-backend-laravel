<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\ListService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class ListController extends Controller
{
    private $listService;
    public function __construct(ListService $listService)
    {
        $this->listService = $listService;
    }

//    public function index(Request $request)
//    {
//        $user_id = $request->user_id;
//
//        try {
//            $list = $this->listService->getAll();
//
//            return response()->json([
//                'status'    => 1,
//                'code'      => Response::HTTP_OK,
//                'data'     => $list
//            ]);
//        }catch (\Exception $err){
//            return response()->json([
//                'status'    => 0,
//                'code'      => Response::HTTP_INTERNAL_SERVER_ERROR,
//                'message'   => $err->getMessage(),
//            ]);
//        }
//    }

    public function show($id){
        try {
            $list = $this->listService->getStudent($id);

            return response()->json([
                'status'    => 1,
                'code'      => Response::HTTP_OK,
                'data'     => $list
            ]);

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
        $status = $request->status;

        $result = DB::update('update list set status = ? where id = ?', [$status, $id]);

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

    public function store(Request $request){
        try {
            $user_id = $request->user_id;
            $status = $request->status;
            $create_date = $request->create_date;
            $content = $request->contents;

            if(isset($user_id)){
                $result = DB::insert("insert into list (user_id, content, create_date, update_at, status) values (?, ?, ?, ?, ?)", [$user_id, $content,$create_date,$create_date, $status]);

                if($result){
                    return response()->json([
                        'status'    => 1,
                        'code'      => Response::HTTP_OK,
                        'message'   => "Add success"
                    ]);
                }else{
                    return response()->json([
                        'status'    => 0,
                        'code'      => Response::HTTP_NOT_FOUND,
                        'message'   => "Add fail"
                    ]);
                }
            }else{
                return response()->json([
                    'status'    => 0,
                    'code'      => Response::HTTP_NOT_FOUND,
                    'message'   => "Not found user"
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

    public function destroy($id)
    {
        $result = DB::table("list")
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
}
