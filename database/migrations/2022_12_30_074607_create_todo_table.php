<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTodoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void {
        Schema::create('todos', function (Blueprint $table) {
            $table->id();
            $table->string("title", 255);
            $table->string("description", 255)->nullable();
            $table->enum("priority", ["LOW", "NORMAL", "HIGH"])->default("NORMAL");
            $table->enum("status", ["PENDING", "COMPLETED"])->default("PENDING");
            $table->dateTime("startDate")->nullable();
            $table->string("photoUrl")->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void {
        Schema::dropIfExists('todos');
    }
}
