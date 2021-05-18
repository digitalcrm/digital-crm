<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateServicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('services', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->longText('description');
            $table->string('image')->nullable();
            $table->integer('price')->default(0)->nullable();
            $table->unsignedInteger('user_id')->nullable();
            $table->foreignId('company_id')->nullable()->constrained();
            $table->foreignId('servcategory_id')->nullable()->constrained();
            $table->string('brand')->nullable();
            $table->text('tags')->nullable();
            $table->integer('views')->nullable();
            $table->boolean('isActive')->default(true);
            $table->boolean('isFeatured')->default(false);
            $table->text('location')->nullable();
            $table->integer('unit_id')->nullable();
            $table->integer('minimum_order')->nullable();
            $table->string('delivery_time')->nullable();
            $table->json('specification')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('user_id')->references('id')->on('users');
            $table->index('user_id');
            $table->index('unit_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('services');
    }
}
