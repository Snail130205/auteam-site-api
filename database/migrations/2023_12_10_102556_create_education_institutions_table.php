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
        Schema::create('education_institutions', function (Blueprint $table) {
            $table->id();
            $table->text('fullName');
            $table->text('position');
            $table->text('educationInstitutionName');
            $table->unsignedBigInteger('educationTypeId');
            $table->foreign('educationTypeId')->references('id')->on('education_types');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('education_institutions');
    }
};
