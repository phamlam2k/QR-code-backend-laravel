<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\UserService;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class UserController extends Controller
{
    private $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function index(Request $request)
    {
        $page = $request->page;
        $limit = $request->limit;

        try {
            $user = $this->userService->getAll($page,$limit);
            return response()->json([
                'status'    => 1,
                'code'      => Response::HTTP_OK,
                'data'     => $user
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
        $id = $request->username;
        $pass = $request->password;

        $result = DB::table('users')->where('email', $id)->where('password', $pass)->get();

        if(count($result) > 0){
            foreach ($result as $value){
                    return response()->json([
                        'code'      => Response::HTTP_OK,
                        'status'    => 1,
                        'data'      => $value,
                        'message'   => "Welcome $value->name !"
                    ]);
            }
        }else{
            return response()->json([
                'code'      => Response::HTTP_INTERNAL_SERVER_ERROR,
                'status'    => 0,
                'data'      => null,
                'message'   => "Fail"
            ]);
        }
    }

    public function show($id){
        $result_id = DB::table('users')->find($id);

        return response()->json([
            'status'    => 1,
            'code'      => Response::HTTP_OK,
            'data'      => $result_id,
        ]);
    }

    public function update(Request $request){
        $id = $request->id;
        $id_user = $request->id_user;
        $name_subject = $request->name;
        $email = $request->email;
        $password = $request->password;

        $result = DB::update('update users set id_student = ?, name = ? ,email = ?,  password = ? where id = ?', [$id_user, $name_subject,$email, $password, $id]);

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
