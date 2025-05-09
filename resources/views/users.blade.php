@extends('layouts.app')

@section('content')
<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-md-10 col-lg-8">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-primary text-white text-center py-3">
                    <h2 class="mb-0 font-weight-bold">Users List</h2>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover table-striped mb-0">
                            <thead class="thead-dark">
                                <tr>
                                    <th scope="col" class="text-center">ID</th>
                                    <th scope="col">Name</th>
                                    <th scope="col">Email</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if($users->isEmpty())
                                <tr>
                                    <td colspan="3" class="text-center py-4">
                                        <div class="alert alert-info mb-0" role="alert">
                                            <i class="fas fa-info-circle me-2"></i> No users found.
                                        </div>
                                    </td>
                                </tr>
                                @else
                                @foreach($users as $user)
                                <tr>
                                    <td class="text-center align-middle">{{ $user->id }}</td>
                                    <td class="align-middle">{{ $user->name }}</td>
                                    <td class="align-middle">{{ $user->email }}</td>
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
</div>
@endsection