@extends('layouts.admin.app', [ 'page' => 'user_management', 'title'=> $title ])

@push('css')

@endpush

@section('content')

<div class="row row-eq-height my-3">

    <x-panel-card class="" >
        <x-slot name="title">
            {{$title}}
        </x-slot>
        
        <form action="{{$action_url}}" method="POST" class="needs-validation @if($errors->any()) was-validated @endif" novalidate>
            @csrf

            <input type="hidden" name="{{$user?'updated_by_id':'created_by_id'}}" value="{{auth()->user()->id}}">

            <div class="form-row">
                <input type="hidden" name="user_id" value="{{$user?$user->id:''}}" >
                <div class="form-group col-12 mb-3">
                    <label for="name" class="w-100 text-left">Name <span class="text-danger">*</span> </label>
                    <input type="text" class="form-control" name="name" placeholder="Name" value="{{$user?$user->name:old('name')}}" required>
                    @error('name')
                        <div class="invalid-feedback"> {{$message}} </div>
                    @enderror
                </div>

                <div class="form-group col-12 mb-3">
                    <label for="email" class="w-100 text-left">Email <span class="text-danger">*</span> </label>
                    <input type="email" class="form-control" name="email" placeholder="Email" value="{{$user?$user->email:old('email')}}" required>
                    @error('email')
                        <div class="invalid-feedback"> {{$message}} </div>
                    @enderror
                </div>

                <div class="form-group col-12 mb-3">
                    <label for="username" class="w-100 text-left">Username <span class="text-danger">*</span> </label>
                    <input type="text" class="form-control" name="username" placeholder="Username" value="{{$user?$user->username:old('username')}}" required>
                    @error('username')
                        <div class="invalid-feedback"> {{$message}} </div>
                    @enderror
                </div>

                <div class="form-group col-12 mb-3">
                    <label for="password" class="col-form-label s-12">Password @if(!$user) <span class="text-danger">*</span> @endif </label>
                    <div class="d-flex align-items-baseline">
                        <div class="w-100">
                            <input type="password" class="form-control r-0 light s-12" name="password" id="password" @if(!$user) required @endif>
                        </div>
                        <button type="button" class="btn btn-success btn-sm d-flex ml-2 open_generate_password_modal" style="white-space: nowrap;">Generate password</button>
                    </div>
                    @error('password')
                        <div class="invalid-feedback"> {{$message}} </div>
                    @else
                        @if( $user )
                            <label>Leave blank if you don't want to change password</label>
                        @endif
                    @enderror
                </div>

                <div class="form-group col-12 mb-3">
                    <label for="phone_number" class="w-100 text-left">Phone <span class="text-danger">*</span> </label>
                    <input type="text" class="form-control" name="phone_number" placeholder="Phone" value="{{$user?$user->phone_number:old('phone_number')}}" required>
                    @error('phone_number')
                        <div class="invalid-feedback"> {{$message}} </div>
                    @enderror
                </div>

                <div class="form-group col-12 mb-3">
                    <label for="role_id" class="w-100 text-left">Role <span class="text-danger">*</span> </label>
                    <select name="role_id" class="form-control" required>
                        <option value="">--select--</option>
                        @foreach ($roles as $role)
                            <option value="{{$role->id}}" {{( ($user && ($user->role_id==$role->id)) || (old('role_id')==$role->id) )?'selected':''}}>{{$role->name}}</option>
                        @endforeach
                    </select>
                    @error('role_id')
                        <div class="invalid-feedback"> {{$message}} </div>
                    @enderror
                </div>

                <div class="form-group col-4 my-2 mb-4">
                    <div class="d-flex justify-content-left align-items-center">
                        <label for="status" class="col-form-label s-12 mr-4">Status</label>
                        <div class="material-switch">
                            <input id="statusOption" name="status" type="checkbox"  {{($user && ($user->status==App\Models\User::STATUS_ACTIVE))?'checked':''}} >
                            <label for="statusOption" class="bg-success"></label>
                        </div>
                    </div>
                </div>

            </div>

            <div>
                <button class="btn btn-primary" type="submit">Submit</button>
            </div>

        </form>

    </x-panel-card>

</div>

@endsection


@section('modals')

<!-- Modal -->
<div class="modal fade" id="generatePasswordModal" tabindex="-1" role="dialog" aria-labelledby="generatePasswordModal" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Generate Password</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-row">
                    <div class="form-group col-12 m-0 mb-2">
                        <label for="role" class="col-form-label s-12">Password </label>
                        <div class="d-flex">
                            <input type="text" class="form-control r-0 light s-12" id="generated_password" readOnly>
                            <button type="button" class="btn btn-sm btn-secondary ml-2" title="Copy Password" onClick="copyToClipboard();"><i class="icon icon-copy pr-0"></i> </button>
                        </div>
                    </div>
                    <div class="form-group col-12 m-0">
                        <button class="btn-sm btn btn-secondary regenerate_password"><i class="icon icon-retweet m-0 pr-1"></i>Re-generate</button>
                        <button class="btn-sm btn btn-success continue_password">Continue</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection


@push('scripts')

<script>

function copyToClipboard() {
    $('#generated_password').select();
    document.execCommand("copy");
    Toast.fire({ title: "Success", text: "Password copied to clipboard", type: "success" });
}

$(document).ready(function() {

    function generatePassword(){
        let newpass = Math.random().toString(36).slice(2);
        $('#generated_password').val(newpass);
    }

    function setGeneratedPassword(){
        let password = $('#generated_password').val();
        $('#password').val(password);
        $('#generatePasswordModal').modal('hide');
    }

    $('.open_generate_password_modal').click(function() {
        generatePassword();
        $('#generatePasswordModal').modal('show');
    });

    $('.continue_password').click(function() {
        setGeneratedPassword();
    });

    $('.regenerate_password').click(function() {
        generatePassword();
    });

});

</script>

@endpush