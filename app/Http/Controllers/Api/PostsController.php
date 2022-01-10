<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\PostService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class PostsController extends Controller
{
    private $postService;
    public function __construct(PostService $postService)
    {
        $this->postService = $postService;
    }

    public function index(Request $request)
    {
        $keyword = $request->keyword;
        $limit = $request->limit;
        $page = $request->page;

        try {
            $student = $this->postService->getAll($page, $limit, $keyword);

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

    public function show($id){
        $result_id = $this->postService->getDetailPost($id);

        return response()->json([
            'status'    => 1,
            'code'      => Response::HTTP_OK,
            'data'      => $result_id,
        ]);
    }

    public function destroy($id)
    {
        $result = DB::table("post")
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
            $title = $request->title;
            $description = $request->description;
            $date = $request->date;
            $permission = $request->permisson;
            $photo = $request->photo;
            $name = $request->name;

            $id_teacher= DB::table('teachers')->where("name","LIKE" ,$name)->first()->id;

            if(isset($id_teacher)){
                DB::insert('insert into post (title, description, photo, id_teacher, date, permisson) values (?, ?, ?, ?, ?, ?)', [$title, $description,$photo,$id_teacher, $date, $permission ]);

                return response()->json([
                    'status'    => 0,
                    'code'      => Response::HTTP_OK,
                    'message'   => "Create post success",
                ]);
            }else{
                return response()->json([
                    'status'    => 0,
                    'code'      => Response::HTTP_NOT_FOUND,
                    'message'   => "Create post fail",
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

    public function update(Request $request, $id)
    {
        $permission = $request->permission;

        $result = DB::update('update post set permisson = ? where id = ?', [$permission, $id]);

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
