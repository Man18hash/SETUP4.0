<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFundedProjectTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('funded_project', function (Blueprint $table) {
            $table->id();
            
            // Project columns
            $table->string('title');
            $table->string('spin_no');
            $table->string('project_type')->default('SETUP4.0');
            $table->text('objective');
            $table->decimal('amount', 15, 2);
            $table->string('plan'); // e.g., 0.5, 1, 2, etc.
            $table->string('status')->default('Checking');
            $table->date('released_date')->nullable();
            
            // Beneficiary columns (combined from the beneficiary table)
            $table->string('firmname')->nullable();
            $table->string('firstname')->nullable();
            $table->string('middlename')->nullable();
            $table->string('lastname')->nullable();
            $table->string('suffix')->nullable();
            $table->string('tel_no')->nullable();
            $table->string('contact_no')->nullable();
            $table->string('tin')->nullable();
            $table->text('address')->nullable();
            $table->string('email')->nullable();
            $table->string('province')->nullable();
            $table->string('sector')->nullable();
            $table->string('category')->nullable();
            $table->text('full_texts')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('funded_project');
    }
}
