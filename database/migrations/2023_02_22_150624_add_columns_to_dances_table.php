<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('dances', function (Blueprint $table) {
            $table->unsignedInteger('position')->after('id')->nullable();
            $table->integer('dancetype_id')->unsigned()->after('id')->nullable();
            $table->foreign('dancetype_id')->references('id')->on('dancetypes');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('dances', function (Blueprint $table) {
            $table->dropColumn('position');
            $table->dropColumn('dancetype_id');
        });
    }
};
