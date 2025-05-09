<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRefundsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('refunds', function (Blueprint $table) {
            $table->id();

            // Reference to the project
            $table->unsignedBigInteger('project_id');
            
            // Basic refund information
            $table->date('refund_date')->nullable();
            $table->decimal('refund_amount', 15, 2)->nullable();
            $table->string('refund_method'); // e.g., 'Post Dated Cheque', 'Cash', 'Cashless', 'Deferred'
            
            // Approval workflow for Post Dated Cheque
            $table->string('approval_status')->default('Pending');
            $table->timestamp('approved_at')->nullable();
            $table->date('check_credit_date')->nullable();
            $table->text('denial_reason')->nullable();

            // For Post Dated Cheque
            $table->string('cheque_number')->nullable();
            $table->date('cheque_date')->nullable();
            $table->string('bank_name')->nullable();
            $table->string('branch')->nullable();

            // For Cash
            $table->string('receipt_number')->nullable();
            $table->string('cashier_name')->nullable();

            // For Cashless (e.g., bank transfer)
            $table->string('bank_account')->nullable();
            $table->string('transaction_reference')->nullable();
            $table->date('transfer_date')->nullable();

            // For Deferred payments
            $table->date('deferred_due_date')->nullable();
            $table->integer('installment_number')->nullable();
            $table->text('installment_schedule')->nullable();

            // Additional remarks
            $table->text('remarks')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('refunds');
    }
}
