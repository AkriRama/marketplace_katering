<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('order_buy', function (Blueprint $table) {
            $table->id();
            $table->string('user_id')->nullable();
            $table->string('order_id')->nullable();
            $table->unsignedBigInteger('product_id')->nullable();
            $table->unsignedBigInteger('service_id')->nullable();
            $table->foreign('product_id')->references('id')->on('products');
            $table->foreign('service_id')->references('id')->on('services');
            $table->string('quantity')->nullable();
            $table->string('table')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_buy');
    }
};
