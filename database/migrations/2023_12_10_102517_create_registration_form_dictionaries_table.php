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
        Schema::create('registration_form_dictionaries', function (Blueprint $table) {
            $table->id();
            $table->text('name');
            $table->text('fieldName');
            $table->text('table');
            $table->integer('number')->nullable();
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
        Schema::dropIfExists('registration_form_dictionaries');
    }
};
