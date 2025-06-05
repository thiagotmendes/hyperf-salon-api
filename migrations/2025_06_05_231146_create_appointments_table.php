<?php

use Hyperf\Database\Schema\Schema;
use Hyperf\Database\Schema\Blueprint;
use Hyperf\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('appointments', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('salon_id');
            $table->unsignedBigInteger('collaborator_id');
            $table->string('client_name');
            $table->string('client_phone', 20);
            $table->date('date');
            $table->time('start_time');
            $table->time('end_time');
            $table->enum('status', ['booked', 'canceled', 'finished'])->default('booked');
            $table->datetimes();

            $table->foreign('salon_id')
                ->references('id')->on('salons')
                ->onDelete('cascade');

            $table->foreign('collaborator_id')
                ->references('id')->on('collaborators')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('appointments');
    }
};
