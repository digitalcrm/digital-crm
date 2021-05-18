<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateServSubcategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('serv_subcategories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug');
            $table->foreignId('servcategory_id')->nullable()->constrained();
            $table->timestamps();
            $table->softDeletes();

            $table->index('servcategory_id');
        });

        Schema::create('service_serv_subcategory', function (Blueprint $table) {
            $table->integer('service_id');
            $table->integer('serv_subcategory_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('serv_subcategories');
        Schema::dropIfExists('service_serv_subcategory');
    }
}
