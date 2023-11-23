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
        Schema::create('requests', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('author_id');
            $table->tinyInteger('status')->default(\App\Models\Request::ACTIVE_STATUS);
            $table->text('message');
            $table->text('answer')->nullable();
            $table->bigInteger('respondent_id')->nullable();
            $table->timestamp('answered_at')->nullable();
            $table->timestamps(); //todo использовать softDeletes для более простого курсора модели
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('requests');
    }
};
