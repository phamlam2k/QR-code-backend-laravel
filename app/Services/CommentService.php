<?php

namespace App\Services;
use App\Models\Comment;

class CommentService {
    public function getAll($page, $limit, $id_post)
    {
        $comment = Comment::with(['student'])
            ->where('id_post', 'LIKE', $id_post)
            ->offset(($page - 1) *10)
            ->paginate($limit);
        for ($i = 0; $i < count($comment); $i ++){
            $comment[$i]['student'] = $comment[$i]->student;
            unset($comment[$i]['id_student']);
        }
        return $comment;
    }
}
