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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table -> string('tradeName');
            $table -> string('quantity');
            $table -> string('status')->default('in processing');
            $table -> string('purchase')->default('Unpaid');
            $table->timestamps();
        });
        
        Schema::create('order_status' , function(Blueprint $table) {
        $table->bigIncrements('id');
        $table->string('status');
        $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
