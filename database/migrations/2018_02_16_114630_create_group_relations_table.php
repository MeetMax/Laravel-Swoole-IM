<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGroupRelationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('group_relations', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('uid');
            $table->integer('group_id');
            $table->tinyInteger('status')->default(1);
            $table->timestamps();
            $table->index('uid');
            $table->index('group_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('group_relations');
    }
}
