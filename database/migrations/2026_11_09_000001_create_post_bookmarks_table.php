<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('post_bookmarks')) {
            return;
        }
        Schema::create('post_bookmarks', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedMediumInteger('userid')->default(0);
            $table->unsignedMediumInteger('topicid')->default(0);
            $table->unsignedInteger('postid')->default(0);
            $table->timestamps();
            $table->unique(['userid', 'postid']);
            $table->index('userid');
            $table->index('topicid');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('post_bookmarks');
    }
};
