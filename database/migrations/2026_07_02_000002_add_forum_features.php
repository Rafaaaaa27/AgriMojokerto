<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Post likes
        Schema::create('forum_post_likes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('forum_post_id')->constrained('forum_posts')->onDelete('cascade');
            $table->timestamps();
            $table->unique(['user_id', 'forum_post_id']);
        });

        // Comment likes
        Schema::create('forum_comment_likes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('forum_comment_id')->constrained('forum_comments')->onDelete('cascade');
            $table->timestamps();
            $table->unique(['user_id', 'forum_comment_id']);
        });

        // Add image and pin to forum_posts
        Schema::table('forum_posts', function (Blueprint $table) {
            $table->string('image_path')->nullable()->after('description');
            $table->boolean('is_pinned')->default(false)->after('views');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('forum_post_likes');
        Schema::dropIfExists('forum_comment_likes');
        Schema::table('forum_posts', function (Blueprint $table) {
            $table->dropColumn(['image_path', 'is_pinned']);
        });
    }
};
