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
        Schema::create('teams', function (Blueprint $table) {
            $table->id();
            $table->text('name');
            $table->boolean('isRegister')->default(false);
            $table->longText('registerHash');
            $table->unsignedBigInteger('teamLeaderId');
            $table->foreign('teamLeaderId')->references('id')->on('team_leaders');
            $table->unsignedBigInteger('educationalInstitutionId');
            $table->foreign('educationalInstitutionId')->references('id')->on('education_institutions');
            $table->unsignedBigInteger('olympiadTypeId');
            $table->foreign('olympiadTypeId')->references('id')->on('olympiad_types');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('teams');
    }
};
