@extends('layouts.app')

@section('content')
<div class="custom-container">
    <div class="custom-header text-center">
        <h2>Profile</h2>
    </div>
    <div class="row justify-content-center">
        <div class="col-md-8">

            <!-- Update Profile Information -->
            <div class="card custom-card">
                <div class="card-body">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            <!-- Update Password -->
            <div class="card custom-card">
                <div class="card-body">
                    @include('profile.partials.update-password-form')
                </div>
            </div>

            <!-- Delete User Account -->
            <div class="card custom-card">
                <div class="card-body">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
