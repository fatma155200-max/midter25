<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTotalPriceToPurchasesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('purchases', function (Blueprint $table) {
            $table->decimal('total_price', 8, 2)->after('quantity');
        });
    }
    
    public function down()
    {
        Schema::table('purchases', function (Blueprint $table) {
            $table->dropColumn('total_price');
        });
    }
    
}
