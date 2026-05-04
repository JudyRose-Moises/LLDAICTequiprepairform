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
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();
            $table->string('subject');
            $table->string('status');
            $table->text('brand')->nullable();
            $table->text('serialNum')->nullable();
            $table->text('equipment_type');
            $table->text('problem')->nullable();
            $table->text('accessories');
            $table->timestamps();
            $table->text('maintenance')->nullable();
            $table->text('action')->nullable();
            $table->text('service')->nullable();
            $table->text('parts')->nullable();
            $table->text('repairedBy')->nullable();
            $table->text('repairDate')->nullable();
            $table->text('noted')->nullable();
            $table->text('first_name')->nullable();
            $table->text('last_name')->nullable();
            $table->text('division')->nullable();
            $table->text('emp_number')->nullable();
            $table->text('propertyID')->nullable();
            $table->text('rating')->nullable();
            $table->timestamp('assignDate')->nullable();
            $table->tinyInteger('urgent')->default(0);
            $table->text('review')->nullable();
            $table->text('for_acceptance')->nullable();
            $table->text('decline_reason')->nullable();
            $table->text('accountableUser')->nullable();
            $table->text('users')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tickets');
    }
};
