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
        $table->string('batch_number')->nullable()->after('supplier');
        $table->date('expiry_date')->nullable()->after('batch_number');
        $table->string('invoice_number')->nullable()->after('expiry_date');
        $table->text('remarks')->nullable()->after('invoice_number');
    });
}

public function down()
{
    Schema::table('purchases', function (Blueprint $table) {
        $table->dropColumn(['batch_number', 'expiry_date', 'invoice_number', 'remarks']);
    });
}

};
