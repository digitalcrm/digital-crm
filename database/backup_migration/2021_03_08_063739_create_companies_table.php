<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCompaniesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('companies', function (Blueprint $table) {
            $table->id();
            $table->string('c_name')->unique();
            $table->string('slug');
            $table->string('personal_name')->nullable();
            $table->string('c_mobileNum')->unique();
            $table->string('c_whatsappNum')->unique();
            $table->string('c_email')->nullable();
            $table->string('c_logo')->nullable()->comment('company logo');
            $table->string('c_cover')->nullable()->comment('comapny cover photo');
            $table->string('position')->nullable()->comment('for eg: CEO, HR');
            $table->text('c_bio')->nullable();
            $table->unsignedInteger('user_id')->nullable();
            $table->integer('actype_id')->nullable()->comment('company type');
            $table->integer('industry_id')->nullable()->comment('business type foreign key from tbl_industrytypes');
            $table->integer('country_id')->nullable();
            $table->integer('state_id')->nullable();
            $table->text('address')->nullable();
            $table->string('city')->nullable();
            $table->string('zipcode')->nullable();
            $table->string('c_gstNumber')->nullable();
            $table->string('c_webUrl')->nullable();
            $table->string('google_map_url')->nullable();
            $table->string('yt_video_link')->nullable();
            $table->string('fb_link')->nullable();
            $table->string('tw_link')->nullable();
            $table->string('yt_link')->nullable();
            $table->string('linkedin_link')->nullable();
            $table->boolean('isActive')->default(true)->comment('compnay enable/disable byDefault enable');
            $table->boolean('isFeatured')->default(false)->comment('is Featured');
            $table->boolean('showLive')->default(true)->comment('company show in front end');
            $table->boolean('termsAccept')->default(false)->comment('terms & conditions');
            $table->integer('employees')->nullable()->comment('number of employeees');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('actype_id')->references('actype_id')->on('tbl_accounttypes');
            // $table->foreign('industry_id')->references('intype_id')->on('tbl_industrytypes');
            $table->foreign('country_id')->references('id')->on('tbl_countries');
            $table->foreign('state_id')->references('id')->on('tbl_states');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('companies');
    }
}
