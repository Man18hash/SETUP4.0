@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h2 class="mb-4">Refund Management</h2>

    <!-- Buttons -->
    <div class="row justify-content-center mb-4">
        <div class="col-md-3 mb-2">
            <button class="btn btn-success w-100">Cash</button>
        </div>
        <div class="col-md-3 mb-2">
            <button class="btn btn-primary w-100">Cashless</button>
        </div>
        <div class="col-md-3 mb-2">
            <button class="btn btn-warning w-100">Post Dated Cheque</button>
        </div>
        <div class="col-md-3 mb-2">
            <button class="btn btn-secondary w-100">Defer</button>
        </div>
    </div>

    <!-- Filter Dropdown -->
    <div class="mb-4">
        <select id="refundFilter" class="form-select">
            <option value="All">All Methods</option>
            <option value="Cash">Cash</option>
            <option value="Cashless">Cashless</option>
            <option value="Post Dated Cheque">Post Dated Cheque</option>
            <option value="Deferred">Deferred</option>
        </select>
    </div>

    <!-- Refunds Table -->
    <table class="table table-bordered" id="refundTable">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Project ID</th>
                <th>Date</th>
                <th>Amount</th>
                <th>Method</th>
                <th>Payment Details</th>
                <th>Remarks</th>
                <th>Created At</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($refunds as $refund)
            <tr data-method="{{ $refund->refund_method }}">
                <td>{{ $refund->id }}</td>
                <td>{{ $refund->project_id }}</td>
                <td>{{ $refund->refund_date }}</td>
                <td>{{ number_format($refund->refund_amount, 2) }}</td>
                <td>{{ $refund->refund_method }}</td>
                <td>
                    @if($refund->isCheque())
                        <strong>Cheque #:</strong> {{ $refund->cheque_number }}<br>
                        <strong>Cheque Date:</strong> {{ $refund->cheque_date }}<br>
                        <strong>Bank:</strong> {{ $refund->bank_name }}<br>
                        @if($refund->branch)
                            <strong>Branch:</strong> {{ $refund->branch }}
                        @endif
                    @elseif($refund->isCash())
                        @if($refund->receipt_number)
                            <strong>Receipt #:</strong> {{ $refund->receipt_number }}<br>
                        @endif
                        @if($refund->cashier_name)
                            <strong>Cashier:</strong> {{ $refund->cashier_name }}<br>
                        @endif
                    @elseif($refund->isCashless())
                        <strong>Bank Account:</strong> {{ $refund->bank_account }}<br>
                        <strong>Bank Name:</strong> {{ $refund->bank_name }}<br>
                        <strong>Transaction Ref:</strong> {{ $refund->transaction_reference }}<br>
                        <strong>Transfer Date:</strong> {{ $refund->transfer_date }}
                    @elseif($refund->isDeferred())
                        <strong>Due Date:</strong> {{ $refund->deferred_due_date }}<br>
                        @if($refund->installment_number)
                            <strong>Installments:</strong> {{ $refund->installment_number }}<br>
                        @endif
                        @if($refund->installment_schedule)
                            <strong>Schedule:</strong> {{ $refund->installment_schedule }}
                        @endif
                    @else
                        N/A
                    @endif
                </td>
                <td>{{ $refund->remarks }}</td>
                <td>{{ $refund->created_at->format('Y-m-d') }}</td>
                <td>{{ $refund->approval_status }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

<script>
document.getElementById('refundFilter').addEventListener('change', function() {
    const method = this.value;
    document.querySelectorAll('#refundTable tbody tr').forEach(row => {
        row.style.display = (method === 'All' || row.dataset.method === method) ? '' : 'none';
    });
});
</script>
@endsection
