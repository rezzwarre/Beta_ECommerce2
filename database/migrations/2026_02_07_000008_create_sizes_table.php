<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        Schema::create('sizes', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->timestamps();
        });

        DB::table('sizes')->insert(array_map(function ($s) {
            return ['name' => $s, 'created_at' => now(), 'updated_at' => now()];
        }, ['S','M','L','XL','XXL']));
    }

    public function down()
    {
        Schema::dropIfExists('sizes');
    }
};
