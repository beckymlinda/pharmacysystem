<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldsToExpensesTable extends Migration
{
    public function up()
    {
        Schema::table('expenses', function (Blueprint $table) {
            $table->date('date')->after('id');
            $table->string('category')->after('date');
            $table->decimal('amount', 15, 2)->after('category');
            $table->text('description')->nullable()->after('amount');
        });
    }

    public function down()
    {
        Schema::table('expenses', function (Blueprint $table) {
            $table->dropColumn(['date', 'category', 'amount', 'description']);
        });
    }
}
