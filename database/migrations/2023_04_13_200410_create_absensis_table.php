<?php

use App\Models\User;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAbsensisTable extends Migration
{
    public function up()
    {
        Schema::create('absensis', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(User::class)->constrained();
            $table->time('pagi')->nullable();
            $table->time('sore')->nullable();
            $table->date('tanggal');
            $table->enum('status', [
                'Hadir',
                'Izin',
                'Cuti',
                'Dinas Luar'
            ]);
            $table->string('keterangan')->nullable();
        });
    }

    public function down()
    {
        Schema::dropIfExists('absensis');
    }
}
