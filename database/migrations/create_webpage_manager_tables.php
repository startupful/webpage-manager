<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('pages', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->longText('content')->nullable();
            $table->string('type')->default('page');
            $table->foreignId('parent_id')->nullable()->constrained('pages')->onDelete('set null');
            $table->boolean('is_published')->default(false);
            $table->timestamp('published_at')->nullable();
            $table->text('meta_data')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('webpage_elements', function (Blueprint $table) {
            $table->id();
            $table->string('type');
            $table->string('name');
            $table->text('code');
            $table->boolean('is_active')->default(false);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('webpage_elements');
        Schema::dropIfExists('pages');
    }
};