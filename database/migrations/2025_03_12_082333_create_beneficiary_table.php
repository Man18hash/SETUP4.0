<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBeneficiaryTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('beneficiary', function (Blueprint $table) {
            $table->id();
            // Removed the custom beneficiary_id column
            $table->string('firmname')->nullable();
            $table->string('firstname')->nullable();
            $table->string('middlename')->nullable();
            $table->string('lastname')->nullable();
            $table->string('suffix')->nullable(); // Added suffix field
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
        Schema::dropIfExists('beneficiary');
    }
}
