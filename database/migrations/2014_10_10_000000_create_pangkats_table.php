<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePangkatsTable extends Migration
{
    public function up()
    {
        Schema::create('pangkats', function (Blueprint $table) {
            $table->id();
            $table->string('golongan');
            $table->date('tmt');
        });
    }

    public function down()
    {
        Schema::dropIfExists('pangkats');
    }
}
