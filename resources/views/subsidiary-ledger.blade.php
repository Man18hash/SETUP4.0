@extends('layouts.app')

@section('content')
<div class="container">
    <!-- Header & Back Button -->
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1>Subsidiary Ledger</h1>
        <a href="{{ route('released-projects') }}" class="btn btn-secondary">Back</a>
    </div>

    <!-- Project / Beneficiary Info & Refund Progress -->
    <div class="card p-3 mb-4">
        <h3>{{ $project->firmname }}</h3>
        <p>
            {{ $project->firstname }} {{ $project->lastname }}
            @if($project->middlename) {{ $project->middlename }} @endif
            @if($project->suffix) {{ $project->suffix }} @endif
        </p>
        <strong>Project Spin No:</strong> {{ $project->spin_no }}<br>
        <strong>Project Type:</strong> {{ $project->project_type }}<br>

        <hr>

        <div class="row">
            <div class="col-md-4">
                <strong>Amount Assisted:</strong>
                <p>₱{{ number_format($project->amount, 2) }}</p>
            </div>
            <div class="col-md-4">
                <strong>Refunded:</strong>
                <p>₱{{ number_format($amountRefundedSoFar, 2) }}</p>
            </div>
            <div class="col-md-4">
                <strong>Balance:</strong>
                <p>₱{{ number_format($project->amount - $amountRefundedSoFar, 2) }}</p>
            </div>
        </div>

        <!-- Refund Progress Bar -->
        @php
            $progressInt = (int) $refundProgress;
        @endphp
        <div class="progress" style="height: 30px;">
            <div class="progress-bar bg-success" 
                 role="progressbar" 
                 style="width: {{ $progressInt }}%;" 
                 aria-valuenow="{{ $progressInt }}" 
                 aria-valuemin="0" 
                 aria-valuemax="100">
                {{ $progressInt }}%
            </div>
        </div>
    </div>

    <!-- Payment Schedule Table -->
    <div class="card p-3">
        <h4>Payment Schedule</h4>
        <div class="table-responsive">
            <table class="table table-bordered align-middle">
                <thead class="thead-dark">
                    <tr>
                        <th>Date Scheduled</th>
                        <th>Monthly Refund</th>
                        <th>Amount Refunded</th>
                        <th>OR No.</th>
                        <th>Balance</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($schedule as $row)
                        <tr>
                            <td>{{ $row['due_date']->format('M d, Y') }}</td>
                            <td>₱{{ number_format($row['monthly_refund'], 2) }}</td>
                            <td>
                                @if($row['amount_refunded'] == 0)
                                    Deferred
                                @else
                                    ₱{{ number_format($row['amount_refunded'], 2) }}
                                @endif
                            </td>
                            <td>{{ $row['or_no'] ?? 'Deferred' }}</td>
                            <td>₱{{ number_format($row['balance'], 2) }}</td>
                            <td>{{ $row['status'] }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
