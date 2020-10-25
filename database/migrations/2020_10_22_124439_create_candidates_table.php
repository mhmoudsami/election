<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCandidatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('candidates', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('mobile')->nullable();
            $table->string('uid');

            $table->string('location')->nullable();
            $table->string('state')->nullable();
            $table->string('police')->nullable();
            $table->string('address')->nullable();
            $table->string('boxnumber')->nullable();
            $table->string('citizen_number')->nullable();
            $table->string('date_round_1')->nullable();
            $table->string('date_round_2')->nullable();
            $table->string('indiv_const')->nullable();
            $table->string('list_const')->nullable();

            $table->json('info')->nullable();
            $table->integer('supervisor_id')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('candidates');
    }
}
