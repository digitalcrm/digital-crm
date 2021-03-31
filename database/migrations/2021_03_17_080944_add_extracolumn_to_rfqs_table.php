<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddExtracolumnToRfqsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('rfqs', function (Blueprint $table) {
            $table->foreignId('company_id')->nullable()->constrained();
            $table->integer('views')->nullable()->default(0)->after('slug')->comment('company views');
            $table->string('rfq_profile')->nullable()->after('views');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('rfqs', function (Blueprint $table) {
            $table->dropForeign(['company_id']);
            $table->dropColumn('views');
            $table->dropColumn('rfq_profile');
        });
    }
}
