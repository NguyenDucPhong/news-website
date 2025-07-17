<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\PostResource;
use App\Models\Post;
use Illuminate\Http\Request;
use App\Enums\PostStatus;
class PostApiController extends Controller
{
    public function index(){
        try {
            $posts = Post::query()
                ->with(['author', 'category'])
                ->where('status', PostStatus::Published)
                ->latest('published_at')
                ->paginate(10);
            return PostResource::collection($posts);
        }catch (\Exception $e) {

            \Log::error('API Get Posts Error: ' . $e->getMessage() . ' at ' . $e->getFile() . ':' . $e->getLine());

            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }

    }
}
