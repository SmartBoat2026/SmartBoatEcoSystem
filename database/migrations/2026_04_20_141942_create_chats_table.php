<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('chats', function (Blueprint $table) {
            $table->id();

            // sender & receiver
            $table->unsignedBigInteger('sender_member_id');
            $table->unsignedBigInteger('receiver_member_id');

            // message content
            $table->text('message')->nullable();

            // message type (text/image/file/audio/video)
            $table->string('type')->default('text');

            // file support (image, pdf, etc)
            $table->string('file_path')->nullable();
            $table->string('file_name')->nullable();
            $table->string('file_type')->nullable();
            $table->bigInteger('file_size')->nullable();

            // status
            $table->boolean('is_seen')->default(false);
            $table->timestamp('seen_at')->nullable();

            // soft delete (optional but useful)
            $table->softDeletes();

            $table->timestamps();

            // indexes (performance boost)
            $table->index(['sender_member_id', 'receiver_member_id']);
            $table->index(['receiver_member_id', 'is_seen']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('chats');
    }
};
