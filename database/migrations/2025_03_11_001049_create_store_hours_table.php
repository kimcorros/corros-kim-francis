<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up()
    {
        Schema::create('store_hours', function (Blueprint $table) {
            $table->id();
            $table->json('open_days');
            $table->time('opening_time');
            $table->time('closing_time');
            $table->time('lunch_break_start');
            $table->time('lunch_break_end');
            $table->boolean('alternate_saturday')->default(false);
            $table->timestamps();
        });

        DB::table('store_hours')->insert([
            'open_days'          => json_encode([
                'monday'    => true,
                'tuesday'   => false,
                'wednesday' => true,
                'thursday'  => false,
                'friday'    => true,
                'saturday'  => false,
                'sunday'    => false,
            ]),
            'opening_time'       => '08:00:00',
            'closing_time'       => '16:00:00',
            'lunch_break_start'  => '12:00:00',
            'lunch_break_end'    => '12:45:00',
            'alternate_saturday' => false,
            'created_at'         => now(),
            'updated_at'         => now(),
        ]);
    }

    public function down()
    {
        Schema::dropIfExists('store_hours');
    }
};
