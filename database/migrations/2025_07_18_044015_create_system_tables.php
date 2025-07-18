<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSystemTables extends Migration
{
   public function up()
{
    Schema::create('contents', function (Blueprint $table) {
        $table->id();
        $table->string('title');
        $table->text('body');
        $table->json('allowed_countries')->nullable();
        $table->datetime('start_time')->nullable();
        $table->datetime('end_time')->nullable();
        $table->integar('is_active')->default(1);
        $table->timestamps();
    });
    
    Schema::create('user_sessions', function (Blueprint $table) {
        $table->id();
        $table->string('session_id');
        $table->unsignedBigInteger('user_id')->nullable();
        $table->string('country_code')->nullable();
        $table->datetime('login_time')->nullable();
        $table->datetime('logout_time')->nullable();
        $table->integer('duration_seconds')->nullable();
        $table->timestamps();
    });
    
    Schema::create('fcm_messages', function (Blueprint $table) {
        $table->id();
        $table->text('message');
        $table->json('data')->nullable();
        $table->timestamps();
    });
}
}
