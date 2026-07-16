@if(isset($post))
<div class="forum-post {{ $pinned ? 'pinned' : '' }}">
    @if($pinned)
    <div class="forum-post-pin">
        <i class="fas fa-thumbtack"></i> Disematkan
    </div>
    @endif

    <div class="forum-post-body">
        <div class="forum-post-avatar">{{ strtoupper(substr($post->category, 0, 1)) }}</div>

        <div class="forum-post-content">
            <div class="forum-post-head">
                <h3 class="forum-post-title">{{ $post->title }}</h3>
                <span class="forum-post-badge">{{ $post->category }}</span>
            </div>

            @if($post->image_path)
            <div class="forum-post-image">
                <img src="{{ asset('storage/' . $post->image_path) }}" alt="{{ $post->title }}">
            </div>
            @endif

            <p class="forum-post-desc">{{ $post->description }}</p>

            <div class="forum-post-meta">
                <div class="forum-post-author">
                    <span class="forum-post-author-avatar">{{ strtoupper(substr($post->user->name, 0, 1)) }}</span>
                    <div>
                        <span class="forum-post-author-name">{{ $post->user->name }}</span>
                        <span class="forum-post-date">{{ $post->created_at->diffForHumans() }}</span>
                    </div>
                </div>

                <div class="forum-post-actions">
                    <span class="forum-post-stat"><i class="far fa-comment"></i> {{ $post->comments->count() }}</span>
                    <button class="forum-like-btn {{ $post->isLikedBy(auth()->user()) ? 'liked' : '' }}" onclick="toggleLike({{ $post->id }}, this)">
                        <i class="fas fa-heart"></i> <span class="like-count">{{ $post->likesCount() }}</span>
                    </button>
                    @auth
                        @if(auth()->id() === $post->user_id || auth()->user()->isAdmin())
                            <button onclick="openEditModal({{ $post->id }}, '{{ addslashes($post->title) }}', '{{ $post->category }}', '{{ addslashes($post->description) }}')" class="forum-post-action-btn" title="Edit">
                                <i class="fas fa-edit"></i>
                            </button>
                            <form action="{{ route('forum.destroy', $post->id) }}" method="POST" onsubmit="return confirm('Hapus diskusi ini? Semua balasan juga akan dihapus.')" style="display:inline;">
                                @csrf @method('DELETE')
                                <button type="submit" class="forum-post-action-btn danger" title="Hapus"><i class="fas fa-trash-alt"></i></button>
                            </form>
                        @endif
                    @endauth
                </div>
            </div>
        </div>
    </div>

    @if($post->comments->count() > 0)
    <div class="forum-comments">
        @foreach($post->comments as $comment)
        <div class="forum-comment">
            <span class="forum-comment-avatar">{{ strtoupper(substr($comment->user->name, 0, 1)) }}</span>
            <div class="forum-comment-body">
                <div class="forum-comment-head">
                    <span class="forum-comment-author">{{ $comment->user->name }}</span>
                    <span class="forum-comment-date">{{ $comment->created_at->diffForHumans() }}</span>
                </div>
                <p class="forum-comment-text">{{ $comment->content }}</p>
                <div class="forum-comment-actions">
                    <button class="forum-like-btn sm {{ $comment->isLikedBy(auth()->user()) ? 'liked' : '' }}" onclick="toggleCommentLike({{ $comment->id }}, this)">
                        <i class="fas fa-heart"></i> <span class="like-count">{{ $comment->likesCount() }}</span>
                    </button>
                    @auth
                        @if(auth()->id() === $comment->user_id || auth()->user()->isAdmin())
                            <form action="{{ route('forum.comment.delete', $comment->id) }}" method="POST" onsubmit="return confirm('Hapus balasan ini?')" style="display:inline;">
                                @csrf @method('DELETE')
                                <button type="submit" class="forum-post-action-btn sm danger"><i class="fas fa-times"></i></button>
                            </form>
                        @endif
                    @endauth
                </div>
            </div>
        </div>
        @endforeach
    </div>
    @endif

    @auth
    <form action="{{ route('forum.reply', $post->id) }}" method="POST" class="forum-reply">
        @csrf
        <input type="text" name="content" placeholder="Tulis balasan..." class="forum-reply-input" required>
        <button type="submit" class="forum-reply-btn"><i class="fas fa-paper-plane"></i></button>
    </form>
    @else
    <div class="forum-reply-guest">
        <a href="{{ route('login') }}">Login</a> untuk membalas diskusi ini.
    </div>
    @endauth
</div>
@endif

@push('styles')
<style>
.forum-post {
    background: var(--surface);
    border: 1px solid var(--border-color);
    border-radius: var(--radius-lg);
    padding: 1.5rem;
    transition: box-shadow 0.25s;
}
.forum-post:hover {
    box-shadow: 0 4px 20px rgba(0,0,0,0.05);
}
.forum-post.pinned {
    border-color: rgba(245,158,11,0.3);
    background: rgba(245,158,11,0.02);
}

.forum-post-pin {
    display: flex;
    align-items: center;
    gap: 0.4rem;
    font-size: 0.7rem;
    font-weight: 700;
    color: var(--warning);
    text-transform: uppercase;
    letter-spacing: 0.5px;
    margin-bottom: 1rem;
}

.forum-post-body {
    display: flex;
    gap: 1.25rem;
    align-items: flex-start;
}

.forum-post-avatar {
    width: 48px;
    height: 48px;
    background: var(--primary);
    color: white;
    border-radius: 14px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.2rem;
    font-weight: 800;
    flex-shrink: 0;
}

.forum-post-content {
    flex: 1;
    min-width: 0;
}

.forum-post-head {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    gap: 0.75rem;
    margin-bottom: 0.5rem;
}

.forum-post-title {
    font-weight: 800;
    font-size: 1.15rem;
    color: var(--text-main);
    line-height: 1.3;
}

.forum-post-badge {
    font-size: 0.6rem;
    font-weight: 800;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    padding: 0.2rem 0.65rem;
    border-radius: 99px;
    background: rgba(5,150,105,0.1);
    color: var(--primary);
    white-space: nowrap;
    flex-shrink: 0;
}

.forum-post-image {
    margin: 0.75rem 0;
    border-radius: var(--radius-md);
    overflow: hidden;
    max-height: 280px;
}
.forum-post-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    max-height: 280px;
    display: block;
}

.forum-post-desc {
    color: var(--text-secondary);
    line-height: 1.65;
    font-size: 0.92rem;
    margin-bottom: 1.25rem;
}

.forum-post-meta {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding-top: 1rem;
    border-top: 1px solid var(--border-color);
    flex-wrap: wrap;
    gap: 0.75rem;
}

.forum-post-author {
    display: flex;
    align-items: center;
    gap: 0.65rem;
}
.forum-post-author-avatar {
    width: 30px;
    height: 30px;
    background: var(--primary);
    color: white;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 0.75rem;
    font-weight: 800;
    flex-shrink: 0;
}
.forum-post-author-name {
    display: block;
    font-weight: 700;
    font-size: 0.82rem;
    color: var(--text-main);
    line-height: 1.2;
}
.forum-post-date {
    font-size: 0.72rem;
    color: var(--text-muted);
}

.forum-post-actions {
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.forum-post-stat {
    font-size: 0.8rem;
    font-weight: 600;
    color: var(--text-muted);
    display: inline-flex;
    align-items: center;
    gap: 0.3rem;
}

.forum-like-btn {
    display: inline-flex;
    align-items: center;
    gap: 0.3rem;
    background: none;
    border: none;
    font-size: 0.8rem;
    font-weight: 600;
    color: var(--text-muted);
    cursor: pointer;
    padding: 0.25rem 0.5rem;
    border-radius: 8px;
    transition: all 0.2s;
    font-family: inherit;
}
.forum-like-btn:hover {
    background: rgba(239,68,68,0.08);
    color: #ef4444;
}
.forum-like-btn.liked {
    color: #ef4444;
}
.forum-like-btn.sm {
    font-size: 0.75rem;
    padding: 0.15rem 0.4rem;
}

.forum-post-action-btn {
    width: 30px;
    height: 30px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    border-radius: 8px;
    background: var(--surface-2);
    border: none;
    color: var(--text-muted);
    cursor: pointer;
    font-size: 0.75rem;
    transition: all 0.2s;
}
.forum-post-action-btn:hover {
    background: var(--primary);
    color: white;
}
.forum-post-action-btn.danger:hover {
    background: #ef4444;
    color: white;
}
.forum-post-action-btn.sm {
    width: 26px;
    height: 26px;
    font-size: 0.65rem;
}

/* Comments */
.forum-comments {
    margin-top: 1.25rem;
    padding: 1rem 1.25rem;
    background: var(--background);
    border-radius: var(--radius-md);
    display: grid;
    gap: 0.75rem;
}
.forum-comment {
    display: flex;
    gap: 0.75rem;
}
.forum-comment + .forum-comment {
    padding-top: 0.75rem;
    border-top: 1px solid var(--border-color);
}
.forum-comment-avatar {
    width: 28px;
    height: 28px;
    background: var(--text-muted);
    color: white;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 0.65rem;
    font-weight: 800;
    flex-shrink: 0;
}
.forum-comment-body {
    flex: 1;
    min-width: 0;
}
.forum-comment-head {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    margin-bottom: 0.2rem;
}
.forum-comment-author {
    font-weight: 700;
    font-size: 0.8rem;
    color: var(--text-main);
}
.forum-comment-date {
    font-size: 0.68rem;
    color: var(--text-muted);
}
.forum-comment-text {
    font-size: 0.88rem;
    color: var(--text-main);
    line-height: 1.55;
    margin-bottom: 0.35rem;
}
.forum-comment-actions {
    display: flex;
    align-items: center;
    gap: 0.35rem;
}

/* Reply */
.forum-reply {
    margin-top: 1rem;
    display: flex;
    gap: 0.65rem;
}
.forum-reply-input {
    flex: 1;
    padding: 0.6rem 1rem;
    border-radius: 10px;
    border: 1.5px solid var(--border-color);
    background: var(--surface-2);
    color: var(--text-main);
    font-size: 0.85rem;
    font-weight: 500;
    outline: none;
    transition: border-color 0.2s;
    font-family: inherit;
}
.forum-reply-input:focus {
    border-color: var(--primary);
}
.forum-reply-btn {
    width: 40px;
    height: 40px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 10px;
    background: var(--primary);
    color: white;
    border: none;
    cursor: pointer;
    font-size: 0.9rem;
    transition: all 0.2s;
    flex-shrink: 0;
}
.forum-reply-btn:hover {
    background: var(--primary-dark);
    box-shadow: 0 4px 12px rgba(5,150,105,0.3);
}

.forum-reply-guest {
    margin-top: 1rem;
    padding: 0.75rem 1rem;
    background: var(--background);
    border-radius: var(--radius-md);
    text-align: center;
    font-size: 0.85rem;
    color: var(--text-muted);
}
.forum-reply-guest a {
    color: var(--primary);
    font-weight: 700;
    text-decoration: none;
}

@media (max-width: 680px) {
    .forum-post {
        padding: 1.1rem;
    }
    .forum-post-body {
        flex-direction: column;
        gap: 0.75rem;
    }
    .forum-post-avatar {
        width: 36px;
        height: 36px;
        font-size: 0.95rem;
        border-radius: 10px;
    }
    .forum-post-head {
        flex-direction: column;
        gap: 0.35rem;
    }
    .forum-post-title {
        font-size: 1rem;
    }
    .forum-post-meta {
        flex-direction: column;
        align-items: flex-start;
    }
    .forum-comments {
        padding: 0.85rem 1rem;
    }
}
</style>
@endpush