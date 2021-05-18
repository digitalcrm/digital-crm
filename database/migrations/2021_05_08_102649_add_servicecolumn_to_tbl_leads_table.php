<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddServicecolumnToTblLeadsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tbl_leads', function (Blueprint $table) {
            $table->integer('service_id')->nullable()->comment('service id store');
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tbl_leads', function (Blueprint $table) {
            $table->dropColumn('service_id');
            $table->dropSoftDeletes();
        });
    }
}
