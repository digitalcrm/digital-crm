<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTodosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('todos', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('title');
            $table->unsignedInteger('todoable_id')->nullable();
            $table->string('todoable_type')->nullable();
            $table->longText('description')->nullable();
            $table->unsignedInteger('admin_id')->nullable();
            $table->unsignedInteger('user_id')->nullable();
            $table->unsignedBigInteger('outcome_id')->nullable();
            $table->unsignedBigInteger('tasktype_id')->nullable();
            $table->string('priority');
            $table->datetime('started_at');
            $table->datetime('due_time');
            $table->datetime('completed_at')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('admin_id')->references('id')->on('admins');
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('outcome_id')->references('id')->on('outcomes');
            $table->foreign('tasktype_id')->references('id')->on('tasktypes');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('todos');
    }
}
