<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::table('purchases', function (Blueprint $table) {
        $table->foreignId('product_id')->constrained()->onDelete('cascade');
        $table->integer('quantity');
        $table->decimal('price', 10, 2);
        $table->decimal('total_cost', 12, 2);
        $table->string('supplier')->nullable();
        $table->date('purchase_date');
    });
}

public function down()
{
    Schema::table('purchases', function (Blueprint $table) {
        $table->dropForeign(['product_id']);
        $table->dropColumn([
            'product_id', 'quantity', 'price', 'total_cost',
            'supplier', 'purchase_date'
        ]);
    });
}

};
