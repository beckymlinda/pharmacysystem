<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('sales', function (Blueprint $table) {
            $table->foreignId('user_id')->after('id')->constrained()->onDelete('cascade');
            $table->foreignId('product_id')->after('user_id')->constrained()->onDelete('cascade');
            $table->integer('quantity')->after('product_id');
            $table->decimal('price', 10, 2)->after('quantity');
            $table->string('payment_method')->after('price');
            $table->decimal('total', 10, 2)->after('payment_method');
        });
    }

    public function down()
    {
        Schema::table('sales', function (Blueprint $table) {
            $table->dropColumn(['user_id', 'product_id', 'quantity', 'price', 'payment_method', 'total']);
        });
    }
};
// This migration adds necessary fields for POS transactions to the sales table.