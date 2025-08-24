<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('machines', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('location')->nullable();
            $table->string('status')->default('idle');
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('machines');
    }
};
