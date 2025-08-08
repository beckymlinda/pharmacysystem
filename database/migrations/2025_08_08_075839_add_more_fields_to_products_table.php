<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->date('expiry_date')->nullable()->after('price');
            $table->decimal('order_price', 10, 2)->nullable()->after('expiry_date');
            $table->decimal('selling_price', 10, 2)->nullable()->after('order_price');
            $table->string('brand')->nullable()->after('selling_price');
            $table->string('seller')->nullable()->after('brand');
            $table->integer('alert_quantity')->default(0)->after('seller');
            $table->integer('purchase_frequency')->default(0)->after('alert_quantity');
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn([
                'expiry_date',
                'order_price',
                'selling_price',
                'brand',
                'seller',
                'alert_quantity',
                'purchase_frequency'
            ]);
        });
    }
};
