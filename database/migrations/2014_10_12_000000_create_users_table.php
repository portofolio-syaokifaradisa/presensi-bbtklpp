<?php

use App\Models\Jabatan;
use App\Models\Pangkat;
use App\Models\Status;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('nip')->nullable();
            $table->string('nik');
            $table->string('email')->unique();
            $table->foreignIdFor(Status::class)->constrained();
            $table->foreignIdFor(Pangkat::class)->constrained();
            $table->foreignIdFor(Jabatan::class)->constrained();
            $table->enum('gender', [
                'Laki-Laki',
                'Perempuan'
            ]);
            $table->text('alamat');
            $table->date('tanggal_lahir');
            $table->string('pendidikan');
            $table->string('gelar');
            $table->enum('role', [
                'user',
                'admin',
                'superadmin'
            ]);
            $table->string('password');
        });
    }

    public function down()
    {
        Schema::dropIfExists('users');
    }
}
