<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSweepTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sweeps', function (Blueprint $table) {
            $table->string('id', 60)->primary();
            $table->string('operation_id', 60)->index('fk_sweep_has_operation');;
            $table->text('path');
            $table->timestamps();
        });

        Schema::table('sweeps', function (Blueprint $table) {
            $table->foreign('operation_id', 'fk_sweep_has_operation')->references('id')->on('operations');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sweeps', function (Blueprint $table) {
            $table->dropForeign('fk_sweep_has_operation');
        });

        Schema::drop('sweeps');
    }
}