<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('article_views', function (Blueprint $table) {
            $table->id();
            $table->foreignId('article_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade');
            $table->string('ip_address')->nullable();
            $table->timestamps();

            $table->unique(['article_id', 'user_id', 'ip_address']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('article_views');
    }
}; 