<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PostController extends Controller
{
    public function index()
    {
        return response()->json(Post::with('user')->get());
    }

    public function show($id)
    {
        $post = Post::with('user')->find($id);
        if (!$post) return response()->json(['error' => 'Post no encontrado'], 404);

        return response()->json($post);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title'   => 'required|string|max:255',
            'content' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $post = Post::create([
            'title'   => $request->title,
            'content' => $request->content,
            'user_id' => auth()->id(),
        ]);

        return response()->json($post, 201);
    }

    public function update(Request $request, $id)
    {
        $post = Post::find($id);
        if (!$post) return response()->json(['error' => 'Post no encontrado'], 404);

        // Asegurar que el usuario es el dueño del post
        if ($post->user_id !== auth()->id()) {
            return response()->json(['error' => 'No autorizado'], 403);
        }

        $post->update($request->only(['title', 'content']));

        return response()->json($post);
    }

    public function destroy($id)
    {
        $post = Post::find($id);
        if (!$post) return response()->json(['error' => 'Post no encontrado'], 404);

        if ($post->user_id !== auth()->id()) {
            return response()->json(['error' => 'No autorizado'], 403);
        }

        $post->delete();

        return response()->json(['message' => 'Post eliminado con éxito']);
    }
}
