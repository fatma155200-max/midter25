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
        Schema::table('users', function (Blueprint $table) {
            // لو العمود موجود مسبقًا، مفيش داعي تضيفه تاني
            if (!Schema::hasColumn('users', 'credit')) {
                $table->decimal('credit', 10, 2)->default(0);
            }
        });
    }
    
    
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('credit');
        });
    }
    
};
