<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('masters', function (Blueprint $table) {
            $table->id();
            $table->string('master_name', 100);
            $table->string('sec_name', 100)->default(null);
            $table->string('master_fam', 100);
            $table->string('master_phone_number', 100);
            $table->string('spec', 100)->default(null);
            $table->dateTimeTz('data_priema', $precision = 0)->nullable();
            $table->dateTimeTz('data_uvoln', $precision = 0)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('masters');
    }
};
