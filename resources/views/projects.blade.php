@extends('layouts.app')

@section('head')
    <link rel="stylesheet" href="{{ asset('css/projects.css') }}">
@endsection

@section('content')
<div class="container mt-4">
    <!-- Action Buttons -->
    <div class="action-buttons-container d-flex justify-content-end mb-3">
        <button type="button" class="btn btn-info mr-2" data-toggle="modal" data-target="#searchProjectModal">Search Project</button>
        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addProjectModal">Add Project</button>
    </div>

    <!-- Filter Form -->
    <form action="{{ route('projects') }}" method="GET" class="mb-4">
        <div class="row">
            <div class="col-md-3">
                <input type="text" name="search" class="form-control" placeholder="Search by title, spin no, or objective">
            </div>
            <div class="col-md-3">
    <select name="filter_plan" class="form-control">
        <option value="">Filter by Plan</option>
        @foreach($project_plans as $plan)
            <option value="{{ $plan->plan_month }}">{{ $plan->plan_month }}</option>
        @endforeach
    </select>
</div>
            <div class="col-md-3">
                <input type="number" name="min_amount" class="form-control" placeholder="Min Amount">
            </div>
            <div class="col-md-3">
                <input type="number" name="max_amount" class="form-control" placeholder="Max Amount">
            </div>
            <div class="col-md-12 mt-2">
                <button type="submit" class="btn btn-secondary btn-block">Filter</button>
            </div>
        </div>
    </form>

    <!-- Projects Table -->
    <div class="container-fluid projects-container">
        <div class="projects-card">
            <div class="projects-card-header">
                <h2 class="projects-title">Projects List</h2>
            </div>
            <div class="projects-card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-sm projects-table">
                        <thead>
                            <tr>
                                <th class="text-center">ID</th>
                                <th>Title</th>
                                <th>Beneficiary</th>
                                <th>Spin No.</th>
                                <th>Objective</th>
                                <th>Amount</th>
                                <th>Plan</th>
                                <th>Released Date</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if($projects->isEmpty())
                                <tr>
                                    <td colspan="10" class="text-center">
                                        <div class="alert alert-info">No projects found.</div>
                                    </td>
                                </tr>
                            @else
                                @foreach($projects as $project)
                                    <tr>
                                        <td class="text-center">{{ $project->id }}</td>
                                        <td>{{ $project->title }}</td>
                                        <td>
                                            @if($project->beneficiaryDetail)
                                                {{ $project->beneficiaryDetail->firmname }}
                                            @else
                                                N/A
                                            @endif
                                        </td>
                                        <td>{{ $project->spin_no }}</td>
                                        <td>{{ $project->objective }}</td>
                                        <td>₱ {{ number_format($project->amount, 2) }}</td>
                                        <td>{{ $project->plan }} {{ $project->plan <= 1 ? 'Year' : 'Years' }}</td>
                                        <td>{{ $project->released_date ?? 'N/A' }}</td>
                                        <td>{{ $project->status }}</td>
                                        <td>
                                            <!-- Edit Button -->
                                            <button type="button" class="btn btn-sm btn-warning editProjectBtn"
                                                data-id="{{ $project->id }}"
                                                data-title="{{ $project->title }}"
                                                data-spin_no="{{ $project->spin_no }}"
                                                data-beneficiary_id="{{ $project->beneficiary_id }}"
                                                data-project_type="{{ $project->project_type }}"
                                                data-objective="{{ $project->objective }}"
                                                data-amount="{{ $project->amount }}"
                                                data-plan="{{ $project->plan }}"
                                                data-status="{{ $project->status }}"
                                                data-released_date="{{ $project->released_date }}">
                                                Edit
                                            </button>
                                            <!-- Attachment Button -->
                                            <button type="button" class="btn btn-sm btn-secondary attachmentProjectBtn"
                                                data-id="{{ $project->id }}"
                                                data-title="{{ $project->title }}"
                                                data-firm="{{ $project->beneficiaryDetail ? $project->beneficiaryDetail->firmname : 'N/A' }}"
                                                data-beneficiary="{{ $project->beneficiaryDetail ? $project->beneficiaryDetail->firstname.' '.$project->beneficiaryDetail->lastname : 'N/A' }}">
                                                Attachment
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

<!-- Add Project Modal -->
<div class="modal fade" id="addProjectModal" tabindex="-1" role="dialog" aria-labelledby="addProjectModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form action="{{ route('projects.store') }}" method="POST" id="addProjectForm">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add Project</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <!-- (Add Project fields: similar to your existing form) -->
                    <div class="form-group">
                        <label for="add_title">Project Title</label>
                        <input type="text" name="title" class="form-control" id="add_title" required>
                    </div>
                    <div class="form-group">
                        <label for="add_spin_no">Spin No.</label>
                        <input type="text" name="spin_no" class="form-control" id="add_spin_no" required>
                    </div>
                    <div class="form-group">
                        <label for="add_beneficiary_id">Beneficiary</label>
                        <select name="beneficiary_id" id="add_beneficiary_id" class="form-control" required>
                            <option value="">Select Beneficiary</option>
                            @foreach($beneficiaries as $ben)
                                <option value="{{ $ben->id }}">{{ $ben->firmname }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="add_project_type">Project Type</label>
                        <select name="project_type" id="add_project_type" class="form-control" required>
                            <option value="SETUP4.0" selected>SETUP4.0</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="add_objective">Objective</label>
                        <textarea name="objective" class="form-control" id="add_objective" required></textarea>
                    </div>
                    <div class="form-group">
                        <label for="add_amount">Project Amount (PHP)</label>
                        <input type="number" step="0.01" name="amount" class="form-control" id="add_amount" required>
                    </div>
                    <div class="form-group">
                        <label for="add_plan">Setup Plan (years)</label>
                        <select name="plan" id="add_plan" class="form-control" required>
                        <option value="">Select Plan</option>
                        @foreach($project_plans as $plan)
                            <option value="{{ $plan->plan_month }}">{{ $plan->plan_month }}</option>
                        @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <button type="button" id="calculateBtnAdd" class="btn btn-secondary btn-block">Calculate</button>
                    </div>
                    <div class="form-group" id="calculationResultsAdd" style="display:none;">
                        <p><strong>Payable Amount:</strong> PHP <span id="payableAmountAdd"></span></p>
                        <p><strong>Monthly Payable Amount:</strong> PHP <span id="monthlyPayableAdd"></span></p>
                    </div>
                    <div class="form-group">
                        <label for="add_status">Status</label>
                        <select name="status" id="add_status" class="form-control" required>
                            <option value="Checking" selected>Checking</option>
                            <option value="Checked">Checked</option>
                            <option value="Approved">Approved</option>
                            <option value="Denied">Denied</option>
                            <option value="Released">Released</option>
                        </select>
                    </div>
                    <div class="form-group" id="releasedDateContainerAdd" style="display:none;">
                        <label for="add_released_date">Released Date</label>
                        <input type="date" name="released_date" class="form-control" id="add_released_date">
                        <small class="form-text text-muted">Select a release date or leave blank to use today's date.</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save Project</button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Edit Project Modal -->
<div class="modal fade" id="editProjectModal" tabindex="-1" role="dialog" aria-labelledby="editProjectModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form action="" method="POST" id="editProjectForm">
            @csrf
            @method('PATCH')
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Project</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <!-- Fields for editing a project -->
                    <div class="form-group">
                        <label for="edit_title">Project Title</label>
                        <input type="text" name="title" class="form-control" id="edit_title" required>
                    </div>
                    <div class="form-group">
                        <label for="edit_spin_no">Spin No.</label>
                        <input type="text" name="spin_no" class="form-control" id="edit_spin_no" required>
                    </div>
                    <div class="form-group">
                        <label for="edit_beneficiary_id">Beneficiary</label>
                        <select name="beneficiary_id" id="edit_beneficiary_id" class="form-control" required>
                            <option value="">Select Beneficiary</option>
                            @foreach($beneficiaries as $ben)
                                <option value="{{ $ben->id }}">{{ $ben->firmname }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="edit_project_type">Project Type</label>
                        <select name="project_type" id="edit_project_type" class="form-control" required>
                            <option value="SETUP4.0" selected>SETUP4.0</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="edit_objective">Objective</label>
                        <textarea name="objective" class="form-control" id="edit_objective" required></textarea>
                    </div>
                    <div class="form-group">
                        <label for="edit_amount">Project Amount (PHP)</label>
                        <input type="number" step="0.01" name="amount" class="form-control" id="edit_amount" required>
                    </div>
                    <div class="form-group">
                        <label for="edit_plan">Setup Plan (years)</label>
                        <select name="plan" id="edit_plan" class="form-control" required>
                            <option value="">Select Plan</option>
                            @foreach($project_plans as $plan)
                                <option value="{{ $plan->plan_month }}">{{ $plan->plan_month }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <button type="button" id="calculateBtnEdit" class="btn btn-secondary btn-block">Calculate</button>
                    </div>
                    <div class="form-group" id="calculationResultsEdit" style="display:none;">
                        <p><strong>Payable Amount:</strong> PHP <span id="payableAmountEdit"></span></p>
                        <p><strong>Monthly Payable Amount:</strong> PHP <span id="monthlyPayableEdit"></span></p>
                    </div>
                    <div class="form-group">
                        <label for="edit_status">Status</label>
                        <select name="status" id="edit_status" class="form-control" required>
                            <option value="Checking">Checking</option>
                            <option value="Checked">Checked</option>
                            <option value="Approved">Approved</option>
                            <option value="Denied">Denied</option>
                            <option value="Released">Released</option>
                        </select>
                    </div>
                    <div class="form-group" id="releasedDateContainerEdit" style="display:none;">
                        <label for="edit_released_date">Released Date</label>
                        <input type="date" name="released_date" class="form-control" id="edit_released_date">
                        <small class="form-text text-muted">Select a release date or leave blank to use today's date.</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Update Project</button>
                </div>
            </div>
        </form>
    </div>
</div>


<!-- Attachment Modal -->
<div class="modal fade" id="attachmentModal" tabindex="-1" role="dialog" aria-labelledby="attachmentModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <form action="{{ route('project_attachments.store') }}" method="POST" id="attachmentForm" enctype="multipart/form-data">
      @csrf
      <input type="hidden" name="project_id" id="attachment_project_id">
      <div class="modal-content">
        <div class="modal-header bg-primary text-white">
          <h5 class="modal-title" id="attachmentModalLabel">Project Attachment</h5>
          <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="container-fluid">
            <!-- Project Details -->
            <div class="row">
              <div class="col-md-6">
                <div class="form-group">
                  <label><strong>Firm Name:</strong></label>
                  <p id="attachment_firm" class="form-control-plaintext"></p>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label><strong>Beneficiary:</strong></label>
                  <p id="attachment_beneficiary" class="form-control-plaintext"></p>
                </div>
              </div>
            </div>
            <!-- File Upload -->
            <div class="row">
              <div class="col-md-12">
                <div class="form-group">
                  <label for="attachment"><strong>Upload Attachment:</strong></label>
                  <input type="file" name="attachment" id="attachment" class="form-control-file" required>
                </div>
              </div>
            </div>
            <hr>
            <!-- Existing Attachments Table -->
            <div class="row">
              <div class="col-md-12">
                <h6>Existing Attachments</h6>
                <div class="table-responsive">
                  <table class="table table-bordered table-hover table-sm" id="attachmentTable">
                    <thead class="thead-light">
                      <tr>
                        <th>Name</th>
                        <th>File Type</th>
                        <th>Date Added</th>
                        <th>Action</th>
                      </tr>
                    </thead>
                    <tbody>
                      <!-- This will be populated dynamically via JavaScript -->
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          </div><!-- end container-fluid -->
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-primary">Save Attachment</button>
        </div>
      </div>
    </form>
  </div>
</div>

<!-- Search Project Modal -->
<div class="modal fade" id="searchProjectModal" tabindex="-1" role="dialog" aria-labelledby="searchProjectModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form action="{{ route('projects') }}" method="GET">
                <div class="modal-header">
                    <h5 class="modal-title">Search Project</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="search_query">Search Query</label>
                        <input type="text" name="search" class="form-control" id="search_query" placeholder="Enter title, spin no, or objective">
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
  // Calculation for Add modal
  document.getElementById('calculateBtnAdd').addEventListener('click', function() {
      var amount = parseFloat(document.getElementById('add_amount').value) || 0;
      var plan = parseFloat(document.getElementById('add_plan').value) || 1;
      var payableAmount = amount;
      var monthlyPayable = amount / (plan * 12);
      document.getElementById('payableAmountAdd').textContent = payableAmount.toFixed(2);
      document.getElementById('monthlyPayableAdd').textContent = monthlyPayable.toFixed(2);
      document.getElementById('calculationResultsAdd').style.display = 'block';
  });

  // Show/hide released date for Add modal
  document.getElementById('add_status').addEventListener('change', function() {
      var container = document.getElementById('releasedDateContainerAdd');
      if (['Released', 'Checked', 'Approved', 'Denied'].includes(this.value)) {
          container.style.display = 'block';
      } else {
          container.style.display = 'none';
          document.getElementById('add_released_date').value = '';
      }
  });

  // When an Edit button is clicked, populate the Edit modal with project data.
  document.querySelectorAll('.editProjectBtn').forEach(function(button) {
      button.addEventListener('click', function() {
          var id = this.getAttribute('data-id');
          var title = this.getAttribute('data-title');
          var spin_no = this.getAttribute('data-spin_no');
          var beneficiary_id = this.getAttribute('data-beneficiary_id');
          var project_type = this.getAttribute('data-project_type');
          var objective = this.getAttribute('data-objective');
          var amount = this.getAttribute('data-amount');
          var plan = this.getAttribute('data-plan');
          var status = this.getAttribute('data-status');
          var released_date = this.getAttribute('data-released_date');

          var form = document.getElementById('editProjectForm');
          form.action = '/projects/' + id;

          document.getElementById('edit_title').value = title;
          document.getElementById('edit_spin_no').value = spin_no;
          document.getElementById('edit_beneficiary_id').value = beneficiary_id;
          document.getElementById('edit_project_type').value = project_type;
          document.getElementById('edit_objective').value = objective;
          document.getElementById('edit_amount').value = amount;
          document.getElementById('edit_plan').value = plan;
          document.getElementById('edit_status').value = status;
          document.getElementById('edit_released_date').value = released_date;

          var container = document.getElementById('releasedDateContainerEdit');
          if (['Released', 'Checked', 'Approved', 'Denied'].includes(status)) {
              container.style.display = 'block';
          } else {
              container.style.display = 'none';
          }
          document.getElementById('calculationResultsEdit').style.display = 'none';

          $('#editProjectModal').modal('show');
      });
  });

  // Calculation for Edit modal.
  document.getElementById('calculateBtnEdit').addEventListener('click', function() {
      var amount = parseFloat(document.getElementById('edit_amount').value) || 0;
      var plan = parseFloat(document.getElementById('edit_plan').value) || 1;
      var payableAmount = amount;
      var monthlyPayable = amount / (plan * 12);
      document.getElementById('payableAmountEdit').textContent = payableAmount.toFixed(2);
      document.getElementById('monthlyPayableEdit').textContent = monthlyPayable.toFixed(2);
      document.getElementById('calculationResultsEdit').style.display = 'block';
  });

  // Show/hide released date for Edit modal when status changes.
  document.getElementById('edit_status').addEventListener('change', function() {
      var container = document.getElementById('releasedDateContainerEdit');
      if (['Released', 'Checked', 'Approved', 'Denied'].includes(this.value)) {
          container.style.display = 'block';
      } else {
          container.style.display = 'none';
          document.getElementById('edit_released_date').value = '';
      }
  });

  // When an Attachment button is clicked, populate the Attachment modal.
  document.querySelectorAll('.attachmentProjectBtn').forEach(function(button) {
      button.addEventListener('click', function() {
          var projectId = this.getAttribute('data-id');
          var firm = this.getAttribute('data-firm');
          var beneficiary = this.getAttribute('data-beneficiary');

          document.getElementById('attachment_project_id').value = projectId;
          document.getElementById('attachment_firm').textContent = firm;
          document.getElementById('attachment_beneficiary').textContent = beneficiary;
          document.getElementById('attachment').value = '';

          // Populate attachments table from a global variable.
          var attachments = window.projectAttachments && window.projectAttachments[projectId] ? window.projectAttachments[projectId] : [];
          var tbody = document.querySelector('#attachmentTable tbody');
          tbody.innerHTML = '';
          attachments.forEach(function(att) {
              var tr = document.createElement('tr');
              tr.innerHTML = '<td>' + att.file_name + '</td>' +
                             '<td>' + att.file_type + '</td>' +
                             '<td>' + att.created_at + '</td>' +
                             '<td><a href="/project-attachments/' + att.id + '/download" class="btn btn-sm btn-primary">Download</a></td>';
              tbody.appendChild(tr);
          });

          $('#attachmentModal').modal('show');
      });
  });

  // Clear attachments table on attachment modal close.
  $('#attachmentModal').on('hidden.bs.modal', function () {
      document.querySelector('#attachmentTable tbody').innerHTML = '';
  });
</script>

<!-- Optional: Embed attachments data as JSON -->
<script>
    window.projectAttachments = @json($projects->keyBy('id')->map(function($proj) {
         return $proj->attachments;
    }));
</script>
@endsection
@section('scripts')
<script>
  // Calculate payable amounts for the project.
  document.getElementById('calculateBtn').addEventListener('click', function() {
      var amount = parseFloat(document.getElementById('amount').value) || 0;
      var plan = parseFloat(document.getElementById('plan').value) || 1;
      var payableAmount = amount;
      var monthlyPayable = amount / (plan * 12);
      document.getElementById('payableAmount').textContent = payableAmount.toFixed(2);
      document.getElementById('monthlyPayable').textContent = monthlyPayable.toFixed(2);
      document.getElementById('calculationResults').style.display = 'block';
  });

  // Show/hide the Released Date field based on status.
  document.getElementById('status').addEventListener('change', function() {
      var container = document.getElementById('releasedDateContainer');
      if (['Released', 'Checked', 'Approved', 'Denied'].includes(this.value)) {
          container.style.display = 'block';
      } else {
          container.style.display = 'none';
          document.getElementById('released_date').value = '';
      }
  });
</script>
@endsection
