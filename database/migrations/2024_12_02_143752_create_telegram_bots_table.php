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
        Schema::create('telegram_bots', function (Blueprint $table) {
            $table->id();
            $table->string('bot_name')->nullable();
            $table->string('bot_token')->nullable();
            $table->integer('connected')->default(0);
            $table->string('reply_message_path')->nullable();
            $table->text('reply_message')->nullable();
            $table->string('reply_message_by_message_id')->nullable();
            $table->string('reply_message_from_telegram_id')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('telegram_bots');
    }
};
