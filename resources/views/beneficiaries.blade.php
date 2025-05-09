@extends('layouts.app')

@section('head')
    <link rel="stylesheet" href="{{ asset('css/projects.css') }}">
@endsection
@section('content')
<div class="container mt-4">
    <!-- Action Buttons -->
    <div class="action-buttons-container d-flex justify-content-end mb-3">
        <!-- Search Beneficiary Button -->
        <button type="button" class="btn btn-info mr-2" data-toggle="modal" data-target="#searchBeneficiaryModal">
            Search Beneficiary
        </button>
        <!-- Add Beneficiary Button -->
        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addBeneficiaryModal">
            Add Beneficiary
        </button>
    </div>

    <!-- Filter Form -->
    <form action="{{ route('beneficiaries') }}" method="GET" class="mb-4">
        <div class="row">
            <div class="col-md-3">
                <select name="filter_sector" class="form-control">
                    <option value="">Filter by Sector</option>
                    <option value="Agriculture/Horticulture">Agriculture/Horticulture</option>
                    <option value="Aquaculture">Aquaculture</option>
                    <option value="Furniture">Furniture</option>
                    <option value="Food/Beverage Processing">Food/Beverage Processing</option>
                    <option value="Gift/Decors/Handicrafts">Gift/Decors/Handicrafts</option>
                    <option value="Health and Wellness Product">Health and Wellness Product</option>
                    <option value="Metal and Engineering">Metal and Engineering</option>
                    <option value="Others">Others</option>
                </select>
            </div>
            <div class="col-md-3">
                <select name="filter_category" class="form-control">
                    <option value="">Filter by Category</option>
                    <option value="Micro">Micro</option>
                    <option value="Small">Small</option>
                    <option value="Medium">Medium</option>
                </select>
            </div>
            <div class="col-md-3">
                <select name="filter_province" class="form-control">
                    <option value="">Filter by Province</option>
                    <option value="Batanes">Batanes</option>
                    <option value="Cagayan">Cagayan</option>
                    <option value="Isabela">Isabela</option>
                    <option value="Quirino">Quirino</option>
                    <option value="Nueva Vizcaya">Nueva Vizcaya</option>
                </select>
            </div>
            <div class="col-md-3">
                <button type="submit" class="btn btn-secondary btn-block">Filter</button>
            </div>
        </div>
    </form>

    <!-- Beneficiaries Table -->
    <div class="container-fluid beneficiaries-container">
        <div class="beneficiaries-card">
            <div class="beneficiaries-card-header">
                <h2 class="beneficiaries-title">Beneficiaries List</h2>
            </div>
            <div class="beneficiaries-card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-sm beneficiaries-table">
                        <thead>
                            <tr>
                                <th class="text-center">ID</th>
                                <th>Firm Name</th>
                                <th>Owner</th>
                                <th>Tel No</th>
                                <th>Contact No</th>
                                <th>TIN</th>
                                <th>Address</th>
                                <th>Email</th>
                                <th>Province</th>
                                <th>Sector</th>
                                <th>Category</th>
                                <th>Full Texts</th>
                                <th>Created At</th>
                                <th>Updated At</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if($beneficiaries->isEmpty())
                                <tr>
                                    <td colspan="14" class="text-center">
                                        <div class="alert alert-info">No beneficiaries found.</div>
                                    </td>
                                </tr>
                            @else
                                @foreach($beneficiaries as $beneficiary)
                                    <tr>
                                        <td class="text-center">{{ $beneficiary->id }}</td>
                                        <td>{{ $beneficiary->firmname }}</td>
                                        <td>
                                            {{ $beneficiary->firstname }}
                                            {{ $beneficiary->middlename }}
                                            {{ $beneficiary->lastname }}
                                            {{ $beneficiary->suffix }}
                                        </td>
                                        <td>{{ $beneficiary->tel_no }}</td>
                                        <td>{{ $beneficiary->contact_no }}</td>
                                        <td>{{ $beneficiary->tin }}</td>
                                        <td>{{ $beneficiary->address }}</td>
                                        <td>{{ $beneficiary->email }}</td>
                                        <td>{{ $beneficiary->province }}</td>
                                        <td>{{ $beneficiary->sector }}</td>
                                        <td>{{ $beneficiary->category }}</td>
                                        <td>{{ $beneficiary->full_texts }}</td>
                                        <td>{{ $beneficiary->created_at }}</td>
                                        <td>{{ $beneficiary->updated_at }}</td>
                                    </tr>
                                @endforeach
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Beneficiary Modal -->
    <div class="modal fade" id="addBeneficiaryModal" tabindex="-1" role="dialog" aria-labelledby="addBeneficiaryModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <form action="{{ route('beneficiaries.store') }}" method="POST">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Add Beneficiary</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <!-- Firm Name -->
                        <div class="form-group">
                            <label for="firmname">Firm Name</label>
                            <input type="text" name="firmname" class="form-control" id="firmname" required>
                        </div>
                        <!-- First Name -->
                        <div class="form-group">
                            <label for="firstname">First Name</label>
                            <input type="text" name="firstname" class="form-control" id="firstname" required>
                        </div>
                        <!-- Middle Name -->
                        <div class="form-group">
                            <label for="middlename">Middle Name</label>
                            <input type="text" name="middlename" class="form-control" id="middlename">
                        </div>
                        <!-- Last Name -->
                        <div class="form-group">
                            <label for="lastname">Last Name</label>
                            <input type="text" name="lastname" class="form-control" id="lastname" required>
                        </div>
                        <!-- Suffix -->
                        <div class="form-group">
                            <label for="suffix">Suffix</label>
                            <input type="text" name="suffix" class="form-control" id="suffix">
                        </div>
                        <!-- TIN -->
                        <div class="form-group">
                            <label for="tin">TIN</label>
                            <input type="text" name="tin" class="form-control" id="tin" required>
                        </div>
                        <!-- Address -->
                        <div class="form-group">
                            <label for="address">Address</label>
                            <textarea name="address" class="form-control" id="address" required></textarea>
                        </div>
                        <!-- Province -->
                        <div class="form-group">
                            <label for="province">Province</label>
                            <input type="text" name="province" class="form-control" id="province" required>
                        </div>
                        <!-- Tel No. -->
                        <div class="form-group">
                            <label for="tel_no">Tel No.</label>
                            <input type="text" name="tel_no" class="form-control" id="tel_no">
                        </div>
                        <!-- Contact No. -->
                        <div class="form-group">
                            <label for="contact_no">Contact No.</label>
                            <input type="text" name="contact_no" class="form-control" id="contact_no" required>
                        </div>
                        <!-- Sector -->
                        <div class="form-group">
                            <label for="sector">Sector</label>
                            <select name="sector" id="sector" class="form-control" required>
                                <option value="">Select Sector</option>
                                <option value="Agriculture/Horticulture">Agriculture/Horticulture</option>
                                <option value="Aquaculture">Aquaculture</option>
                                <option value="Furniture">Furniture</option>
                                <option value="Food/Beverage Processing">Food/Beverage Processing</option>
                                <option value="Gift/Decors/Handicrafts">Gift/Decors/Handicrafts</option>
                                <option value="Health and Wellness Product">Health and Wellness Product</option>
                                <option value="Metal and Engineering">Metal and Engineering</option>
                                <option value="Others">Others</option>
                            </select>
                            <input type="hidden" name="sector_custom" id="sector_custom" value="">
                        </div>
                        <!-- Category -->
                        <div class="form-group">
                            <label for="category">Category</label>
                            <select name="category" id="category" class="form-control" required>
                                <option value="">Select Category</option>
                                <option value="Micro">Micro</option>
                                <option value="Small">Small</option>
                                <option value="Medium">Medium</option>
                            </select>
                        </div>
                        <!-- Email -->
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" name="email" class="form-control" id="email" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Save Beneficiary</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Others Sector Modal -->
    <div class="modal fade" id="othersSectorModal" tabindex="-1" role="dialog" aria-labelledby="othersSectorModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Custom Sector</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Exit">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="custom_sector_input">Please specify your sector</label>
                        <input type="text" class="form-control" id="custom_sector_input" placeholder="Enter custom sector">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal" id="exitOthers">Exit</button>
                    <button type="button" class="btn btn-primary" id="saveOthers">Save</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Search Beneficiary Modal -->
    <div class="modal fade" id="searchBeneficiaryModal" tabindex="-1" role="dialog" aria-labelledby="searchBeneficiaryModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form action="{{ route('beneficiaries') }}" method="GET">
                    <div class="modal-header">
                        <h5 class="modal-title">Search Beneficiary</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="search_query">Search Query</label>
                            <input type="text" name="search" class="form-control" id="search_query" placeholder="Enter name, TIN, or email">
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
</div>
@endsection

@section('scripts')
<script>
    // When the sector select changes in the Add Beneficiary modal
    document.getElementById('sector').addEventListener('change', function() {
        if (this.value === 'Others') {
            $('#othersSectorModal').modal('show');
        } else {
            document.getElementById('sector_custom').value = '';
        }
    });

    // Save custom sector from the Others Sector modal
    document.getElementById('saveOthers').addEventListener('click', function() {
        var customSector = document.getElementById('custom_sector_input').value.trim();
        if (customSector === '') {
            alert('Please enter a custom sector.');
            return;
        }
        document.getElementById('sector_custom').value = customSector;
        var sectorSelect = document.getElementById('sector');
        var existingCustomOption = document.querySelector('#sector option[data-custom]');
        if(existingCustomOption) {
            existingCustomOption.remove();
        }
        var newOption = document.createElement("option");
        newOption.value = customSector;
        newOption.text = customSector;
        newOption.setAttribute('data-custom', 'true');
        newOption.selected = true;
        sectorSelect.add(newOption);
        $('#othersSectorModal').modal('hide');
    });

    // If user clicks Exit in the Others Sector modal, revert the selection
    document.getElementById('exitOthers').addEventListener('click', function() {
        document.getElementById('sector').value = '';
    });
</script>
@endsection
