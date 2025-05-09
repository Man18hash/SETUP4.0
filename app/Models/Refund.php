<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Refund extends Model
{
    protected $fillable = [
        'project_id', 'refund_date', 'refund_amount', 'refund_method', 'approval_status', 'approved_at', 'check_credit_date',
        'denial_reason', 'cheque_number', 'cheque_date', 'bank_name', 'branch', 'receipt_number', 'cashier_name',
        'bank_account', 'transaction_reference', 'transfer_date', 'deferred_due_date', 'installment_number', 'installment_schedule', 'remarks'
    ];

    // Relation with Project (assuming Project model exists)
    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    // Check methods
    public function isCash()
    {
        return $this->refund_method === 'Cash';
    }

    public function isCashless()
    {
        return $this->refund_method === 'Cashless';
    }

    public function isCheque()
    {
        return $this->refund_method === 'Post Dated Cheque';
    }

    public function isDeferred()
    {
        return $this->refund_method === 'Deferred';
    }
}
