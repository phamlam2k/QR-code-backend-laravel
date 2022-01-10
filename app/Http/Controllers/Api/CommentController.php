<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\CommentService;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class CommentController extends Controller
{
    private $commentService;
    public function __construct(CommentService $commentService)
    {
        $this->commentService = $commentService;
    }

    public function index(Request $request)
    {
        $id_post = $request->id_post;
        $limit = $request->limit;
        $page = $request->page;

        try {
            $comment = $this->commentService->getAll($page, $limit, $id_post);

            return response()->json([
                'status'    => 1,
                'code'      => Response::HTTP_OK,
                'data'     => $comment
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
            $student = $request->student;
            $comment = $request->comment;
            $create_at = $request->created_at;
            $id_post = $request->id_post;

            $id_student = DB::table('student')->where("id_student","LIKE" , $student)->first()->id;

            if(isset($id_student)) {
                DB::insert('insert into comment_post (id_post, id_student, comment, date) values (?, ?,  ?, ?)', [$id_post, $id_student ,$comment, $create_at ]);

                return response()->json([
                    'status'    => 0,
                    'code'      => Response::HTTP_OK,
                    'message'   => "Comment success",
                ]);
            }else{
                return response()->json([
                    'status'    => 0,
                    'code'      => Response::HTTP_NOT_FOUND,
                    'message'   => "Comment fail",
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
