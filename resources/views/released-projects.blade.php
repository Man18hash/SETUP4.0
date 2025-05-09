@extends('layouts.app')

@section('head')
    <link rel="stylesheet" href="{{ asset('css/projects.css') }}">
@endsection

@section('content')
<div class="container mt-4">
    <!-- Action Buttons -->
    <div class="action-buttons-container d-flex justify-content-end mb-3">
        <button type="button" class="btn btn-info mr-2" data-toggle="modal" data-target="#searchProjectModal">Search Released Projects</button>
    </div>

    <!-- Filter Form -->
    <form action="{{ route('released-projects') }}" method="GET" class="mb-4">
        <div class="row">
            <div class="col-md-3">
                <input type="text" name="search" class="form-control" placeholder="Search by title, spin no, objective, or firm name" value="{{ old('search', $searchTerm) }}">
            </div>
            <div class="col-md-12 mt-2">
                <button type="submit" class="btn btn-secondary btn-block">Filter</button>
            </div>
        </div>
    </form>

    <!-- Released Projects Table -->
    <div class="container-fluid projects-container">
        <div class="projects-card">
            <div class="projects-card-header">
                <h2 class="projects-title">Released Projects List</h2>
            </div>
            <div class="projects-card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-sm projects-table">
                        <thead>
                            <tr>
                                <th class="text-center">ID</th>
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
                            @if($projects->isEmpty())
                                <tr>
                                    <td colspan="10" class="text-center">
                                        <div class="alert alert-info">No released projects found.</div>
                                    </td>
                                </tr>
                            @else
                                @foreach($projects as $project)
                                    <tr>
                                        <td class="text-center">{{ $project->id }}</td>
                                        <td>
                                            @if($project->firmname)
                                                <strong>Firm:</strong> {{ $project->firmname }} <br>
                                                <strong>Owner:</strong> {{ $project->lastname }}, {{ $project->firstname }}
                                                @if($project->middlename)
                                                    {{ $project->middlename }}
                                                @endif
                                                @if($project->suffix)
                                                    {{ $project->suffix }}
                                                @endif
                                            @else
                                                N/A
                                            @endif
                                        </td>
                                        <td>
                                            <strong>Project Title:</strong> {{ $project->title }} <br>
                                            <strong>Spin No.:</strong> {{ $project->spin_no }} <br>
                                            @if($project->released_date)
                                                <strong>Date Released:</strong> {{ \Carbon\Carbon::parse($project->released_date)->format('M d, Y') }}
                                            @endif
                                        </td>
                                        <td>{{ $project->sector }}</td>
                                        <td>{{ $project->category }}</td>
                                        <td>₱{{ number_format($project->amount,2) }}</td>
                                        <td>
                                            @if($project->released_date)
                                                {{ \Carbon\Carbon::parse($project->released_date)->format('Y') }}
                                            @else
                                                N/A
                                            @endif
                                        </td>
                                        <td>{{ $project->status }}</td>
                                        <td>
                                            @if(isset($project->refund_status))
                                                @php
                                                    // Example: use badge classes as needed
                                                    $refundBadgeClass = '';
                                                    switch($project->refund_status) {
                                                        case 'Newly Added':
                                                            $refundBadgeClass = 'badge-primary';
                                                            break;
                                                        case 'Active':
                                                            $refundBadgeClass = 'badge-info';
                                                            break;
                                                        case 'Delinquent':
                                                            $refundBadgeClass = 'badge-warning';
                                                            break;
                                                        case 'Terminated/Pullout':
                                                            $refundBadgeClass = 'badge-danger';
                                                            break;
                                                        case 'Endorsed to OSG':
                                                            $refundBadgeClass = 'badge-dark';
                                                            break;
                                                        case 'Fully Paid':
                                                            $refundBadgeClass = 'badge-success';
                                                            break;
                                                        default:
                                                            $refundBadgeClass = 'badge-secondary';
                                                    }
                                                @endphp
                                                <span class="badge {{ $refundBadgeClass }}">{{ $project->refund_status }}</span>
                                            @else
                                                <span class="badge badge-secondary">N/A</span>
                                            @endif
                                        </td>
                                        <td>
                                            <!-- Single Action Button -->
                                            <button type="button" class="btn btn-sm btn-secondary" onclick="openProjectActionModal({{ $project->id }}, '{{ addslashes($project->title) }}', '{{ addslashes($project->firmname) }}', '{{ addslashes($project->lastname . ', ' . $project->firstname) }}', '{{ $project->refund_status ?? '' }}')">
                                                Action
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Project Action Modal -->
<div class="modal fade" id="projectActionModal" tabindex="-1" role="dialog" aria-labelledby="projectActionModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="projectActionModalLabel">Project Actions</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <p id="projectInfo"></p>
        <div class="btn-group btn-group-vertical w-100">
          <button type="button" class="btn btn-primary" id="btnEditRefundStatus">Edit Refund Status</button>
          <button type="button" class="btn btn-secondary" id="btnManageAttachment">Manage Attachment</button>
          <a href="#" class="btn btn-info" id="btnSubsidiaryLedger">Subsidiary Ledger</a>
          <button type="button" class="btn btn-danger" id="btnDeleteProject">Delete</button>
        </div>
      </div>
      <div class="modal-footer">
         <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<!-- Edit Refund Status Modal -->
<div class="modal fade" id="editRefundStatusModal" tabindex="-1" role="dialog" aria-labelledby="editRefundStatusModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <form id="refundStatusForm" method="POST" action="">
      @csrf
      @method('PATCH')
      <div class="modal-content">
         <div class="modal-header">
           <h5 class="modal-title" id="editRefundStatusModalLabel">Edit Refund Status</h5>
           <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
           </button>
         </div>
         <div class="modal-body">
           <label for="refund_status" class="form-label">Choose Refund Status</label>
           <select class="form-control" name="refund_status" id="refund_status">
             <option value="">-- Select --</option>
             <option value="Newly Added">Newly Added</option>
             <option value="Active">Active</option>
             <option value="Delinquent">Delinquent</option>
             <option value="Terminated/Pullout">Terminated/Pullout</option>
             <option value="Endorsed to OSG">Endorsed to OSG</option>
             <option value="Fully Paid">Fully Paid</option>
           </select>
         </div>
         <div class="modal-footer">
           <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
           <button type="submit" class="btn btn-primary">Save</button>
         </div>
      </div>
    </form>
  </div>
</div>

<!-- Manage Attachment Modal -->
<div class="modal fade" id="manageAttachmentModal" tabindex="-1" role="dialog" aria-labelledby="manageAttachmentModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <form id="attachmentForm" method="POST" action="" enctype="multipart/form-data">
      @csrf
      <div class="modal-content">
         <div class="modal-header">
           <h5 class="modal-title" id="manageAttachmentModalLabel">Manage Attachment</h5>
           <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
           </button>
         </div>
         <div class="modal-body">
           <!-- Project & Beneficiary Info -->
           <div class="mb-3">
             <strong>Project Title:</strong> <span id="attProjectTitle"></span><br>
             <strong>Firm Name:</strong> <span id="attFirmName"></span><br>
             <strong>Owner:</strong> <span id="attOwnerName"></span>
           </div>
           <!-- File Upload -->
           <div class="mb-3">
             <label for="attachment" class="form-label">Choose Attachment</label>
             <input type="file" name="attachment" id="attachment" class="form-control">
             <small class="form-text text-muted">A folder named "ProjectName - BeneficiaryName" will be created automatically.</small>
           </div>
           <!-- Attachment History -->
           <div class="mb-3">
             <h6>Attachment History</h6>
             <table class="table table-bordered">
               <thead>
                 <tr>
                   <th>File Name</th>
                   <th>Date Uploaded</th>
                   <th>Action</th>
                 </tr>
               </thead>
               <tbody id="attachmentHistory">
                 <tr>
                   <td colspan="3"><em>No attachments found.</em></td>
                 </tr>
               </tbody>
             </table>
           </div>
         </div>
         <div class="modal-footer">
           <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
           <button type="submit" class="btn btn-primary">Save Attachment</button>
         </div>
      </div>
    </form>
  </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteProjectModal" tabindex="-1" role="dialog" aria-labelledby="deleteProjectModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <form id="deleteProjectForm" method="POST" action="">
      @csrf
      @method('DELETE')
      <div class="modal-content">
         <div class="modal-header">
           <h5 class="modal-title" id="deleteProjectModalLabel">Delete Project</h5>
           <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
           </button>
         </div>
         <div class="modal-body">
           <p>Are you sure you want to delete this project?</p>
         </div>
         <div class="modal-footer">
           <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
           <button type="submit" class="btn btn-danger">Delete</button>
         </div>
      </div>
    </form>
  </div>
</div>

<!-- Search Released Projects Modal -->
<div class="modal fade" id="searchProjectModal" tabindex="-1" role="dialog" aria-labelledby="searchProjectModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <form action="{{ route('released-projects') }}" method="GET">
        <div class="modal-header">
          <h5 class="modal-title" id="searchProjectModalLabel">Search Released Projects</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        </div>
        <div class="modal-body">
          <div class="form-group">
            <label for="search_query">Search Query</label>
            <input type="text" name="search" class="form-control" id="search_query" placeholder="Enter title, spin no, objective, or firm name">
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-primary">Search</button>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection

@section('scripts')
<script>
  // Global variable for current project id
  var currentProjectId = null;

  // Function to open the Project Action Modal and fill its data
  function openProjectActionModal(id, title, firmname, owner, refundStatus) {
      currentProjectId = id;
      $('#projectInfo').text('Project: ' + title + ' (Beneficiary: ' + firmname + ')');
      $('#btnSubsidiaryLedger').attr('href', '/subsidiary-ledger/' + id);
      $('#projectActionModal').modal('show');
  }

  $(document).ready(function(){
      // When "Edit Refund Status" is clicked
      $('#btnEditRefundStatus').click(function(){
          $('#refundStatusForm').attr('action', '/released-projects/' + currentProjectId + '/refund-status');
          $('#projectActionModal').modal('hide');
          $('#editRefundStatusModal').modal('show');
      });

      // When "Manage Attachment" is clicked
      $('#btnManageAttachment').click(function(){
          $('#attachmentForm').attr('action', '/released-projects/' + currentProjectId + '/attachments');
          $('#projectActionModal').modal('hide');
          $('#manageAttachmentModal').modal('show');
      });

      // When "Delete" is clicked
      $('#btnDeleteProject').click(function(){
          $('#deleteProjectForm').attr('action', '/released-projects/' + currentProjectId);
          $('#projectActionModal').modal('hide');
          $('#deleteProjectModal').modal('show');
      });
  });
</script>
@endsection
