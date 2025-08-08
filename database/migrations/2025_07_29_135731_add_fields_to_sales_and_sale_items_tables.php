
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldsToSalesAndSaleItemsTables extends Migration
{
    public function up()
    {
        // Add columns to sales table
        Schema::table('sales', function (Blueprint $table) {
            $table->decimal('total_amount', 10, 2)->after('id')->default(0);
            $table->timestamp('sale_date')->nullable()->after('total_amount');
        });

        // Add columns to sale_items table
        Schema::table('sale_items', function (Blueprint $table) {
            $table->foreignId('sale_id')->nullable()->constrained('sales')->onDelete('cascade')->after('id');
            $table->foreignId('product_id')->nullable()->constrained('products')->after('sale_id');
            $table->integer('quantity')->default(1)->after('product_id');
            $table->decimal('price', 10, 2)->default(0)->after('quantity');
            $table->decimal('subtotal', 10, 2)->default(0)->after('price');
        });
    }

    public function down()
    {
        Schema::table('sale_items', function (Blueprint $table) {
            $table->dropForeign(['sale_id']);
            $table->dropForeign(['product_id']);
            $table->dropColumn(['sale_id', 'product_id', 'quantity', 'price', 'subtotal']);
        });

        Schema::table('sales', function (Blueprint $table) {
            $table->dropColumn(['total_amount', 'sale_date']);
        });
    }
}
