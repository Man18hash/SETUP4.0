<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ReleasedProject;
use Carbon\Carbon;

class SubsidiaryLedgerController extends Controller
{
    /**
     * Display a subsidiary ledger for the given project ID.
     */
    public function show($id)
    {
        // Fetch the project from the funded_project table
        $project = ReleasedProject::findOrFail($id);

        // Convert plan (years) to an integer number of months
        // e.g. plan=2 => 24 months
        // If plan is fractional (e.g. 1.5), we handle that as well.
        $planYears = floatval($project->plan);
        $totalMonths = (int) ceil($planYears * 12);

        // Calculate the monthly payment
        // For example, total amount / total months
        // If the user wants interest or other logic, adjust accordingly
        $monthlyPayment = $project->amount / $totalMonths;

        // We'll generate a schedule array for each month
        // Starting from the project's released_date or "now" if none
        $releaseDate = $project->released_date
            ? Carbon::parse($project->released_date)
            : Carbon::now();

        // Placeholder for actual refunded amounts.
        // In a real app, you might store these in a payments table.
        // For now, we'll assume no payments have been made.
        $amountRefundedSoFar = 0;

        // Build the schedule array
        $schedule = [];
        for ($i = 1; $i <= $totalMonths; $i++) {
            // The due date for each monthly payment
            $dueDate = $releaseDate->copy()->addMonths($i);

            // Example: everything is "Deferred" if not paid
            $schedule[] = [
                'due_date'        => $dueDate,
                'monthly_refund'  => $monthlyPayment,
                'amount_refunded' => 0,          // or retrieve from your actual data
                'or_no'           => null,       // placeholder or actual data
                'balance'         => $project->amount - ($i * $monthlyPayment),
                'status'          => 'Deferred', // e.g. "Deferred", "Paid", "Overdue"
            ];
        }

        // Example: "refund progress" is (amountRefundedSoFar / totalAmount) * 100
        // If you are storing actual payments, sum them up to get $amountRefundedSoFar.
        $refundProgress = 0;
        if ($project->amount > 0) {
            $refundProgress = ($amountRefundedSoFar / $project->amount) * 100;
        }

        return view('subsidiary-ledger', [
            'project'          => $project,
            'schedule'         => $schedule,
            'refundProgress'   => $refundProgress,
            'amountRefundedSoFar' => $amountRefundedSoFar,
        ]);
    }
}
