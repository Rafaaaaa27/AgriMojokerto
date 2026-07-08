@if(isset($post))
<div class="glass-card animate-fade" style="padding: 2.5rem; border: 1px solid {{ $pinned ? 'rgba(245, 158, 11, 0.3)' : 'var(--border-color)' }}; {{ $pinned ? 'background: rgba(245, 158, 11, 0.03);' : '' }}">
    @if($pinned)
    <div style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 1rem;">
        <span style="background: rgba(245, 158, 11, 0.1); color: #d97706; padding: 0.2rem 0.75rem; border-radius: 99px; font-size: 0.65rem; font-weight: 800; text-transform: uppercase; letter-spacing: 0.5px;">
            <i class="fas fa-thumbtack"></i> Disematkan
        </span>
    </div>
    @endif

    <div style="display: flex; gap: 1.5rem; align-items: flex-start;">
        <div style="width: 60px; height: 60px; background: var(--background); border-radius: 18px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; color: var(--primary); font-size: 1.5rem;">
            {{ strtoupper(substr($post->category, 0, 1)) }}
        </div>
        <div style="flex: 1;">
            <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 0.5rem;">
                <h3 style="font-weight: 800; font-size: 1.5rem; color: var(--primary-dark); line-height: 1.2;">{{ $post->title }}</h3>
                <span class="badge" style="background: rgba(16, 185, 129, 0.1); color: var(--primary); font-size: 0.65rem;">{{ strtoupper($post->category) }}</span>
            </div>

            @if($post->image_path)
            <div style="margin-bottom: 1rem; border-radius: var(--radius-md); overflow: hidden; max-height: 300px;">
                <img src="{{ asset('storage/' . $post->image_path) }}" style="width: 100%; height: 100%; object-fit: cover; max-height: 300px;">
            </div>
            @endif

            <p style="color: var(--text-main); line-height: 1.7; margin-bottom: 2rem; font-size: 1.05rem;">
                {{ $post->description }}
            </p>
            <div style="display: flex; justify-content: space-between; align-items: center; padding-top: 1.5rem; border-top: 1px solid var(--border-color);">
                <div style="display: flex; align-items: center; gap: 1rem; flex-wrap: wrap;">
                    <div style="display: flex; align-items: center; gap: 0.75rem;">
                        <div style="width: 35px; height: 35px; background: var(--primary); color: white; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 0.9rem; font-weight: 800;">
                            {{ strtoupper(substr($post->user->name, 0, 1)) }}
                        </div>
                        <div>
                            <div style="font-weight: 700; font-size: 0.9rem;">{{ $post->user->name }}</div>
                            <div style="font-size: 0.75rem; color: var(--text-muted);">{{ $post->created_at->diffForHumans() }}</div>
                        </div>
                    </div>
                    <div style="display: flex; gap: 1.5rem; color: var(--text-muted); font-size: 0.9rem; font-weight: 600;">
                        <span><i class="far fa-comment"></i> {{ $post->comments->count() }} Balasan</span>
                        <button class="like-btn {{ $post->isLikedBy(auth()->user()) ? 'liked' : '' }}" style="background: none; border: none; font-weight: 600; font-size: 0.9rem; color: var(--text-muted); display: inline-flex; align-items: center; gap: 0.3rem;" onclick="toggleLike({{ $post->id }}, this)">
                            <i class="fas fa-heart"></i> <span class="like-count">{{ $post->likesCount() }}</span>
                        </button>
                    </div>
                </div>
                <div style="display: flex; gap: 0.5rem;">
                    @auth
                        @if(auth()->id() === $post->user_id || auth()->user()->isAdmin())
                            <button onclick="openEditModal({{ $post->id }}, '{{ addslashes($post->title) }}', '{{ $post->category }}', '{{ addslashes($post->description) }}')" class="action-btn-icon" title="Edit">
                                <i class="fas fa-edit"></i>
                            </button>
                            <form action="{{ route('forum.destroy', $post->id) }}" method="POST" onsubmit="return confirm('Hapus diskusi ini? Semua balasan juga akan dihapus.')" style="display: inline;">
                                @csrf @method('DELETE')
                                <button type="submit" class="action-btn-icon action-btn-danger" title="Hapus">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </form>
                        @endif
                    @endauth
                </div>
            </div>
        </div>
    </div>

    <!-- COMMENTS SECTION -->
    @if($post->comments->count() > 0)
    <div style="margin-top: 2rem; padding: 1.5rem; background: var(--background); border-radius: var(--radius-lg);">
        @foreach($post->comments as $comment)
        <div style="display: flex; gap: 1rem; margin-bottom: 1rem; padding-bottom: 1rem; border-bottom: 1px solid rgba(0,0,0,0.05);">
            <div style="width: 30px; height: 30px; background: #cbd5e1; color: white; border-radius: 8px; display: flex; align-items: center; justify-content: center; font-size: 0.75rem; flex-shrink: 0;">
                {{ strtoupper(substr($comment->user->name, 0, 1)) }}
            </div>
            <div style="flex: 1;">
                <div style="display: flex; justify-content: space-between; align-items: flex-start;">
                    <div>
                        <div style="font-size: 0.85rem; font-weight: 800; margin-bottom: 0.25rem;">{{ $comment->user->name }}</div>
                        <div style="font-size: 0.9rem; color: var(--text-main); line-height: 1.5;">{{ $comment->content }}</div>
                    </div>
                    <div style="display: flex; gap: 0.5rem; align-items: center;">
                        <button class="like-btn {{ $comment->isLikedBy(auth()->user()) ? 'liked' : '' }}" style="background: none; border: none; font-weight: 600; font-size: 0.8rem; color: var(--text-muted); display: inline-flex; align-items: center; gap: 0.3rem;" onclick="toggleCommentLike({{ $comment->id }}, this)">
                            <i class="fas fa-heart"></i> <span class="like-count">{{ $comment->likesCount() }}</span>
                        </button>
                        @auth
                            @if(auth()->id() === $comment->user_id || auth()->user()->isAdmin())
                            <form action="{{ route('forum.comment.delete', $comment->id) }}" method="POST" onsubmit="return confirm('Hapus balasan ini?')" style="display: inline;">
                                @csrf @method('DELETE')
                                <button type="submit" class="action-btn-icon action-btn-danger" title="Hapus" style="font-size: 0.8rem;">
                                    <i class="fas fa-times"></i>
                                </button>
                            </form>
                            @endif
                        @endauth
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    @endif

    <!-- REPLY FORM -->
    @auth
    <form action="{{ route('forum.reply', $post->id) }}" method="POST" style="margin-top: 1.5rem; display: flex; gap: 1rem;">
        @csrf
        <input type="text" name="content" placeholder="Tulis balasan..." class="form-input" style="flex: 1; padding: 0.75rem 1.25rem;" required>
        <button type="submit" class="btn btn-primary" style="padding: 0.75rem 1.5rem;"><i class="fas fa-paper-plane"></i></button>
    </form>
    @else
    <div style="margin-top: 1.5rem; padding: 1rem; background: var(--background); border-radius: var(--radius-md); text-align: center; color: var(--text-muted); font-size: 0.9rem;">
        <a href="{{ route('login') }}" style="color: var(--primary); font-weight: 700;">Login</a> untuk membalas diskusi ini.
    </div>
    @endauth
</div>
@endif
