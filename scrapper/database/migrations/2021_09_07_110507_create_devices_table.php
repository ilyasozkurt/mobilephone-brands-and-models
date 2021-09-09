<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDevicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('devices', function (Blueprint $table) {
            $table->id();
            $table->string('url_hash')->index('url_hash');
            $table->integer('brand_id')->index('brand_id');
            $table->string('name')->index('name');
            $table->string('picture')->nullable();
            $table->string('released_at')->nullable();
            $table->string('body')->nullable();
            $table->string('os')->nullable();
            $table->string('storage')->nullable();
            $table->string('display_size')->nullable();
            $table->string('display_resolution')->nullable();
            $table->string('camera_pixels')->nullable();
            $table->string('video_pixels')->nullable();
            $table->string('ram')->nullable();
            $table->string('chipset')->nullable();
            $table->string('battery_size')->nullable();
            $table->string('battery_type')->nullable();
            $table->text('specifications');
            $table->softDeletes();
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
        Schema::dropIfExists('devices');
    }
}
