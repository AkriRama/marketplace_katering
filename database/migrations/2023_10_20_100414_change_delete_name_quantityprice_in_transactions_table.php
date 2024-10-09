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
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropColumn('code_product', 'name_product', 'quantity_price');
            $table->string('name')->after('order_id')->nullable();
            $table->string('totalOrder')->after('total')->nullable();
            $table->unsignedBigInteger('product_id')->after('order_id')->nullable();
            $table->unsignedBigInteger('service_id')->after('product_id')->nullable();
            $table->foreign('product_id')->references('id')->on('products');
            $table->foreign('service_id')->references('id')->on('services');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->string('code_product')->nullable();
            $table->string('name_product')->nullable();
            $table->string('quantity_price')->nullable();
            $table->dropForeign('transaction_product_id_foreign', 'transaction_service_id_foreign');
            $table->dropColumn('name', 'totalOrder', 'product_id', 'service_id');
        });
    }
};
