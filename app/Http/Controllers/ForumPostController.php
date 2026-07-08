<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ForumPost;
use App\Models\ForumComment;
use App\Models\ForumPostLike;
use App\Models\ForumCommentLike;
use Illuminate\Support\Facades\Storage;

class ForumPostController extends Controller
{
    public function index(Request $request)
    {
        $query = ForumPost::with('user', 'comments.user', 'likes');

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Category filter
        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        // Sort
        $sort = $request->sort ?? 'terbaru';
        match ($sort) {
            'terpopuler' => $query->withCount('likes')->orderBy('likes_count', 'desc'),
            'terbanyak' => $query->withCount('comments')->orderBy('comments_count', 'desc'),
            default => $query->latest(),
        };

        // Pinned posts always on top
        $pinned = (clone $query)->where('is_pinned', true)->get();
        $posts = $query->where('is_pinned', false)->paginate(10)->withQueryString();

        $categoryCounts = ForumPost::selectRaw('category, count(*) as count')
            ->groupBy('category')
            ->pluck('count', 'category')
            ->toArray();

        return view('forum.index', compact('posts', 'pinned', 'categoryCounts'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'required|string',
            'description' => 'required|string',
            'image' => 'nullable|image|max:2048',
        ]);

        $data = [
            'user_id' => auth()->id(),
            'title' => $request->title,
            'category' => $request->category,
            'description' => $request->description,
        ];

        if ($request->hasFile('image')) {
            $data['image_path'] = $request->file('image')->store('forum', 'public');
        }

        ForumPost::create($data);

        return redirect()->back()->with('success', 'Diskusi berhasil dibuat!');
    }

    public function update(Request $request, ForumPost $post)
    {
        if ($post->user_id !== auth()->id() && !auth()->user()->isAdmin()) {
            abort(403);
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'required|string',
            'description' => 'required|string',
            'image' => 'nullable|image|max:2048',
        ]);

        $data = $request->only('title', 'category', 'description');

        if ($request->hasFile('image')) {
            if ($post->image_path) {
                Storage::disk('public')->delete($post->image_path);
            }
            $data['image_path'] = $request->file('image')->store('forum', 'public');
        }

        $post->update($data);

        return redirect()->back()->with('success', 'Diskusi berhasil diperbarui!');
    }

    public function destroy(ForumPost $post)
    {
        if ($post->user_id !== auth()->id() && !auth()->user()->isAdmin()) {
            abort(403);
        }

        if ($post->image_path) {
            Storage::disk('public')->delete($post->image_path);
        }

        $post->comments()->delete();
        $post->likes()->delete();
        $post->delete();

        return redirect()->back()->with('success', 'Diskusi berhasil dihapus.');
    }

    public function reply(Request $request, ForumPost $post)
    {
        $request->validate([
            'content' => 'required|string',
        ]);

        ForumComment::create([
            'user_id' => auth()->id(),
            'forum_post_id' => $post->id,
            'content' => $request->content,
        ]);

        return redirect()->back()->with('success', 'Balasan berhasil dikirim!');
    }

    public function deleteComment(ForumComment $comment)
    {
        if ($comment->user_id !== auth()->id() && !auth()->user()->isAdmin()) {
            abort(403);
        }

        $comment->likes()->delete();
        $comment->delete();

        return redirect()->back()->with('success', 'Balasan berhasil dihapus.');
    }

    public function toggleLike(Request $request, ForumPost $post)
    {
        $like = ForumPostLike::where('user_id', auth()->id())
            ->where('forum_post_id', $post->id)
            ->first();

        if ($like) {
            $like->delete();
            $liked = false;
        } else {
            ForumPostLike::create([
                'user_id' => auth()->id(),
                'forum_post_id' => $post->id,
            ]);
            $liked = true;
        }

        if ($request->ajax()) {
            return response()->json([
                'liked' => $liked,
                'count' => $post->likes()->count(),
            ]);
        }

        return redirect()->back();
    }

    public function toggleCommentLike(Request $request, ForumComment $comment)
    {
        $like = ForumCommentLike::where('user_id', auth()->id())
            ->where('forum_comment_id', $comment->id)
            ->first();

        if ($like) {
            $like->delete();
            $liked = false;
        } else {
            ForumCommentLike::create([
                'user_id' => auth()->id(),
                'forum_comment_id' => $comment->id,
            ]);
            $liked = true;
        }

        if ($request->ajax()) {
            return response()->json([
                'liked' => $liked,
                'count' => $comment->likes()->count(),
            ]);
        }

        return redirect()->back();
    }
}
