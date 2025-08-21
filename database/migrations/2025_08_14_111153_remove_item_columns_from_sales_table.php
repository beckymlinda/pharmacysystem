

<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('sales', function (Blueprint $table) {
            // Drop columns that belong to sale_items
            $table->dropColumn(['product_id', 'quantity', 'price', 'total']);
        });
    }

    public function down(): void
    {
        Schema::table('sales', function (Blueprint $table) {
            // Recreate columns if we rollback
            $table->unsignedBigInteger('product_id')->nullable();
            $table->integer('quantity')->nullable();
            $table->decimal('price', 12, 2)->nullable();
            $table->decimal('total', 12, 2)->nullable();
        });
    }
};
