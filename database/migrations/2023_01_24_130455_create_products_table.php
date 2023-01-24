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
        Schema::create('products', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id');
            $table->foreignId('category_id');

            $table->string('name');
            $table->double('price');
            $table->timestamp('end_datetime');
            $table->double('min_increment')->default(0);
            $table->double('max_increment')->default(0);

            $table->text('short_desc')->nullable();
            $table->text('terms')->nullable();

            $table->boolean('status')->default(0);

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
        Schema::dropIfExists('products');
    }
};
