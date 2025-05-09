@extends('layouts.app')

@section('content')
    <h1>Funded Projects</h1>

    <!-- Search Form -->
    <form action="{{ route('funded-projects') }}" method="GET" class="mb-4">
        <div class="input-group">
            <input 
                type="text" 
                name="search" 
                class="form-control" 
                placeholder="Search Funded Projects..." 
                value="{{ old('search', $searchTerm) }}"
            >
            <button class="btn btn-primary" type="submit">Search</button>
        </div>
    </form>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if($projects->count() > 0)
        <table class="table table-bordered table-hover align-middle">
            <thead>
                <tr>
                    <th>Beneficiary</th>
                    <th>Project Detail</th>
                    <th>Sector</th>
                    <th>Category</th>
                    <th>Cost</th>
                    <th>Year</th>
                    <th>Status</th>
                    <th>Refund Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($projects as $project)
                    <tr>
                        <!-- Beneficiary -->
                        <td>
                            @if($project->beneficiaryDetail)
                                <strong>Firm:</strong> {{ $project->beneficiaryDetail->firmname }} <br>
                                <strong>Owner:</strong>
                                {{ $project->beneficiaryDetail->lastname }}, 
                                {{ $project->beneficiaryDetail->firstname }}
                                @if($project->beneficiaryDetail->middlename)
                                    {{ $project->beneficiaryDetail->middlename }}
                                @endif
                                @if($project->beneficiaryDetail->suffix)
                                    {{ $project->beneficiaryDetail->suffix }}
                                @endif
                            @else
                                <em>No Beneficiary Assigned</em>
                            @endif
                        </td>

                        <!-- Project Detail -->
                        <td>
                            <strong>Project Title:</strong> {{ $project->title }} <br>
                            <strong>Spin No.:</strong> {{ $project->spin_no }} <br>
                            @if($project->released_date)
                                <strong>Date Released:</strong> 
                                {{ \Carbon\Carbon::parse($project->released_date)->format('M d, Y') }}
                            @endif
                        </td>

                        <!-- Sector -->
                        <td>
                            @if($project->beneficiaryDetail)
                                {{ $project->beneficiaryDetail->sector }}
                            @endif
                        </td>

                        <!-- Category -->
                        <td>
                            @if($project->beneficiaryDetail)
                                {{ $project->beneficiaryDetail->category }}
                            @endif
                        </td>

                        <!-- Cost -->
                        <td>
                            ₱{{ number_format($project->amount, 2) }}
                        </td>

                        <!-- Year -->
                        <td>
                            @if($project->released_date)
                                {{ \Carbon\Carbon::parse($project->released_date)->format('Y') }}
                            @else
                                <em>N/A</em>
                            @endif
                        </td>

                        <!-- Status -->
                        <td>
                            <!-- Example: Display status as a badge -->
                            @if($project->status === 'Released')
                                <span class="badge bg-success">{{ $project->status }}</span>
                            @else
                                <span class="badge bg-secondary">{{ $project->status }}</span>
                            @endif
                        </td>

                        <!-- Refund Status -->
                        <td>
                            @if(isset($project->refund_status))
                                @php
                                    // Example: color-coded badges
                                    $refundBadgeClass = match($project->refund_status) {
                                        'Newly Added' => 'bg-primary',
                                        'Fully Paid' => 'bg-success',
                                        'Active' => 'bg-info',
                                        'Delinquent' => 'bg-warning text-dark',
                                        'Terminated/Pullout' => 'bg-danger',
                                        'Endorsed to OSG' => 'bg-dark',
                                        default => 'bg-secondary',
                                    };
                                @endphp
                                <span class="badge {{ $refundBadgeClass }}">
                                    {{ $project->refund_status }}
                                </span>
                            @else
                                <span class="badge bg-secondary">N/A</span>
                            @endif
                        </td>

                        <!-- Action Dropdown -->
                        <td>
                            <div class="dropdown">
                                <button class="btn btn-secondary dropdown-toggle" 
                                        type="button" 
                                        id="actionDropdown" 
                                        data-bs-toggle="dropdown" 
                                        aria-expanded="false">
                                    Action
                                </button>
                                <ul class="dropdown-menu" aria-labelledby="actionDropdown">
                                    <li>
                                        <a href="#" 
                                           class="dropdown-item"
                                           data-bs-toggle="modal" 
                                           data-bs-target="#editRefundStatusModal"
                                           data-project-id="{{ $project->id }}"
                                           data-current-status="{{ $project->refund_status ?? '' }}">
                                            Edit Refund Status
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#" 
                                           class="dropdown-item"
                                           data-bs-toggle="modal" 
                                           data-bs-target="#manageAttachmentModal"
                                           data-project-id="{{ $project->id }}"
                                           data-project-title="{{ $project->title }}"
                                           data-firmname="{{ $project->beneficiaryDetail->firmname ?? '' }}"
                                           data-owner="{{ $project->beneficiaryDetail ? $project->beneficiaryDetail->lastname . ', ' . $project->beneficiaryDetail->firstname : '' }}">
                                            Manage Attachment
                                        </a>
                                    </li>
                                    <li>
                                        <!-- Stub: Link to subsidiary ledger function -->
                                        <a href="#" class="dropdown-item">
                                            Subsidiary Ledger
                                        </a>
                                    </li>
                                    <li>
                                        <!-- Stub: Link to print subsidiary ledger function -->
                                        <a href="#" class="dropdown-item">
                                            Print Subsidiary Ledger
                                        </a>
                                    </li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li>
                                        <!-- Stub: Delete link or form -->
                                        <a href="#" class="dropdown-item text-danger">
                                            Delete
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <p>No released projects found.</p>
    @endif

    <!-- =================== -->
    <!-- EDIT REFUND STATUS MODAL -->
    <!-- =================== -->
    <div class="modal fade" id="editRefundStatusModal" tabindex="-1" aria-labelledby="editRefundStatusModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form id="refundStatusForm" method="POST" action="">
                @csrf
                @method('PATCH')

                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editRefundStatusModalLabel">Edit Refund Status</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <label for="refund_status" class="form-label">Choose Refund Status</label>
                        <select class="form-select" name="refund_status" id="refund_status">
                            <option value="">-- Select --</option>
                            <option value="Newly Added">Newly Added</option>
                            <option value="Fully Paid">Fully Paid</option>
                            <option value="Active">Active</option>
                            <option value="Delinquent">Delinquent</option>
                            <option value="Terminated/Pullout">Terminated/Pullout</option>
                            <option value="Endorsed to OSG">Endorsed to OSG</option>
                        </select>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Save</button>
                    </div>
                </div><!-- modal-content -->
            </form>
        </div>
    </div><!-- modal -->

    <!-- =================== -->
    <!-- MANAGE ATTACHMENT MODAL -->
    <!-- =================== -->
    <div class="modal fade" id="manageAttachmentModal" tabindex="-1" aria-labelledby="manageAttachmentModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <form id="attachmentForm" method="POST" action="" enctype="multipart/form-data">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="manageAttachmentModalLabel">Manage Attachment</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <!-- Project & Beneficiary Info -->
                        <div class="mb-3">
                            <strong>Project Title:</strong> <span id="projectTitle"></span><br>
                            <strong>Firm Name:</strong> <span id="firmName"></span><br>
                            <strong>Owner:</strong> <span id="ownerName"></span>
                        </div>

                        <!-- Tabs (Upload / History) -->
                        <ul class="nav nav-tabs" id="attachmentTabs" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" id="upload-tab" data-bs-toggle="tab" data-bs-target="#uploadTab" type="button" role="tab" aria-controls="uploadTab" aria-selected="true">
                                    Upload
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="history-tab" data-bs-toggle="tab" data-bs-target="#historyTab" type="button" role="tab" aria-controls="historyTab" aria-selected="false">
                                    History
                                </button>
                            </li>
                        </ul>
                        <div class="tab-content" id="attachmentTabsContent">
                            <!-- Upload Tab -->
                            <div class="tab-pane fade show active p-3" id="uploadTab" role="tabpanel" aria-labelledby="upload-tab">
                                <div class="mb-3">
                                    <label for="attachment" class="form-label">Choose file to upload</label>
                                    <input type="file" name="attachment" id="attachment" class="form-control">
                                </div>
                            </div>

                            <!-- History Tab -->
                            <div class="tab-pane fade p-3" id="historyTab" role="tabpanel" aria-labelledby="history-tab">
                                <!-- Example: show previously uploaded files. 
                                     You can fetch them via an AJAX request or pass them to the modal. -->
                                <p>No previous files yet (stub).</p>
                            </div>
                        </div>
                    </div><!-- modal-body -->
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Upload</button>
                    </div>
                </div><!-- modal-content -->
            </form>
        </div>
    </div><!-- modal -->

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Edit Refund Status Modal: fill form action & select current status
    var editRefundStatusModal = document.getElementById('editRefundStatusModal');
    editRefundStatusModal.addEventListener('show.bs.modal', function (event) {
        var button = event.relatedTarget;
        var projectId = button.getAttribute('data-project-id');
        var currentStatus = button.getAttribute('data-current-status');

        // Update the form action to the correct project ID route
        var form = editRefundStatusModal.querySelector('#refundStatusForm');
        form.action = "/funded-projects/" + projectId + "/refund-status";

        // Set the dropdown to the current status
        var refundStatusSelect = editRefundStatusModal.querySelector('#refund_status');
        refundStatusSelect.value = currentStatus ? currentStatus : "";
    });

    // Manage Attachment Modal: fill form action & project details
    var manageAttachmentModal = document.getElementById('manageAttachmentModal');
    manageAttachmentModal.addEventListener('show.bs.modal', function (event) {
        var button = event.relatedTarget;
        var projectId = button.getAttribute('data-project-id');
        var projectTitle = button.getAttribute('data-project-title');
        var firmName = button.getAttribute('data-firmname');
        var ownerName = button.getAttribute('data-owner');

        // Fill in text placeholders
        manageAttachmentModal.querySelector('#projectTitle').textContent = projectTitle;
        manageAttachmentModal.querySelector('#firmName').textContent = firmName;
        manageAttachmentModal.querySelector('#ownerName').textContent = ownerName;

        // Update the form action to the correct project ID route
        var attachmentForm = manageAttachmentModal.querySelector('#attachmentForm');
        attachmentForm.action = "/funded-projects/" + projectId + "/attachments";
    });
});
</script>
@endpush
