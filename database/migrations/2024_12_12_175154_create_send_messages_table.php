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
        Schema::create('send_messages', function (Blueprint $table) {
            $table->id();
            $table->string('title')->nullable();
            $table->dateTime('schedule_send_time')->nullable();
            $table->string('message_type')->nullable();
            $table->string('message_id')->nullable();
            $table->string('telegram_id')->nullable();
            $table->string('message_path')->nullable();
            $table->text('message')->nullable();
            $table->boolean('is_sent')->default(0);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('send_messages');
    }
};
