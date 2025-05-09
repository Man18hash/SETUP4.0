<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProjectsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('spin_no');
            // This field references the beneficiary's auto-increment id.
            $table->unsignedBigInteger('beneficiary_id');
            $table->string('project_type')->default('SETUP4.0');
            $table->text('objective');
            $table->decimal('amount', 15, 2);
            $table->string('plan'); // e.g., 0.5, 1, 2, etc.
            $table->string('status')->default('Checking');
            $table->date('released_date')->nullable();
            $table->timestamps();

            // Foreign key constraint: beneficiary_id references beneficiary.id.
            $table->foreign('beneficiary_id')->references('id')->on('beneficiary')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('projects');
    }
}
