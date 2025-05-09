@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Project Plans Setup</h1>
    <!-- Button to trigger Add modal -->
    <button class="btn btn-primary mb-3" data-toggle="modal" data-target="#addModal">
        Add Project Plan
    </button>

    <!-- Project Plans Table -->
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Plan Years</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($plans as $plan)
            <tr>
                <td>{{ $plan->id }}</td>
                <td>{{ $plan->plan_month }}</td>
                <td>
                    <!-- Edit button triggers the edit modal -->
                    <button class="btn btn-secondary edit-btn" 
                            data-id="{{ $plan->id }}" 
                            data-plan_month="{{ $plan->plan_month }}"
                            data-toggle="modal" 
                            data-target="#editModal">
                        Edit
                    </button>
                    <!-- Delete button triggers the delete modal -->
                    <button class="btn btn-danger delete-btn" 
                            data-id="{{ $plan->id }}"
                            data-toggle="modal" 
                            data-target="#deleteModal">
                        Delete
                    </button>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

<!-- Add Modal -->
<div class="modal fade" id="addModal" tabindex="-1" aria-labelledby="addModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('project-plans.store') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="addModalLabel">Add Project Plan</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="plan_month" class="form-label">Plan Years</label>
                        <input type="number" step="any" class="form-control" name="plan_month" 
                               id="plan_month" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" 
                            data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Modal -->
<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" 
     aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <!-- The form action will be set dynamically via JavaScript -->
            <form id="editForm" method="POST">
                @csrf
                @method('PATCH')
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel">Edit Project Plan</h5>
                    <button type="button" class="close" data-dismiss="modal" 
                            aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="edit_plan_month" class="form-label">Plan Years</label>
                        <input type="number" step="any" class="form-control" name="plan_month" 
                               id="edit_plan_month" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" 
                            data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Delete Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" 
     aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <!-- The form action will be set dynamically via JavaScript -->
            <form id="deleteForm" method="POST">
                @csrf
                @method('DELETE')
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteModalLabel">Delete Project Plan</h5>
                    <button type="button" class="close" data-dismiss="modal" 
                            aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to delete this project plan?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" 
                            data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">Confirm Delete</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Inline JavaScript to handle modal form actions -->
<script>
    // Set up the edit modal with the current plan data
    document.querySelectorAll('.edit-btn').forEach(button => {
        button.addEventListener('click', function() {
            const id = this.getAttribute('data-id');
            const planMonth = this.getAttribute('data-plan_month');
            const editForm = document.getElementById('editForm');
            // Update the form action to point to the correct update route
            editForm.action = `/project-plans/${id}`;
            document.getElementById('edit_plan_month').value = planMonth;
        });
    });

    // Set up the delete modal with the current plan id
    document.querySelectorAll('.delete-btn').forEach(button => {
        button.addEventListener('click', function() {
            const id = this.getAttribute('data-id');
            const deleteForm = document.getElementById('deleteForm');
            // Update the form action to point to the correct delete route
            deleteForm.action = `/project-plans/${id}`;
        });
    });
</script>
@endsection
