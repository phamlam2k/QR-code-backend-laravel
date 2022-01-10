<?php

namespace App\Services;

use App\Models\Post;

class PostService {
    public function getAll($page, $limit, $keyword)
    {
        $post = Post::with(['teacher'])
            ->where('title', 'LIKE', "%{$keyword}%")
            ->offset(($page - 1)*10)
            ->paginate($limit);
        for ($i = 0; $i < count($post); $i ++){
            $post[$i]['teacher'] = $post[$i]->teacher;
            unset($post[$i]['id_teacher']);
        }
        return $post;
    }

    public function getDetailPost($id)
    {
        $post = Post::all();
        for ($i = 0; $i < count($post); $i ++){
            $post[$i]['teacher'] = $post[$i]->teacher;
            unset($post[$i]['id_teacher']);
        }

        return $post->find($id);
    }
}
