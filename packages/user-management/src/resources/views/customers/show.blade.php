@extends('layouts.admin.app', [ 'page' => 'customer_management', 'title'=> $title ])

@push('css')

@endpush

@section('content')

<div class="row row-eq-height my-3">

    <x-panel-card class="col-md-6" >
        <x-slot name="title">
            {{$title}}
        </x-slot>

        <dl>
            
            <dt>Name</dt>
            <dd>{{$user->name}}</dd>

            <dt>Email</dt>
            <dd>{{$user->email}}</dd>

            <dt>Username</dt>
            <dd>{{$user->username}}</dd>

            <dt>Phone Number</dt>
            <dd>{{$user->phone_number}}</dd>

            <dt>Role</dt>
            <dd>{{$user->role->name}}</dd>

            <dt>Date Joined</dt>
            <dd>{{$user->m_created_at}} (Last Login: {{$user->last_login??'n/a'}})</dd>

            <dt>NIC image</dt>
            <dd><img src="{{$user->cnic_front_image}}" alt="" width=200px>
            <img src="{{$user->cnic_back_image}}" alt="" width=200px></dd>

            <dt>Status</dt>
            <dd>{!! $user->getStatusHtml() !!}</dd>

        </dl>
        
        <x-slot name="footer">
            <div class="d-flex justify-content-start">
                <a href="{{$action_url}}" class="btn btn-primary btn-sm">Go Back</a>
            </div>
        </x-slot>

    </x-panel-card>

    @if( App\Models\User::canEdit('customer') )
    <x-panel-card class="col-md-6" >
        <x-slot name="title">
            Customer Update
        </x-slot>
        <div class="">

            <form class="form-horizontal" action="{{$update_status_url}}" method="POST">
                @csrf
                <div class="form-group">
                    <label for="inputName" class="col-sm-2 control-label">Status</label>
        
                    <div class="col-sm-10">
                        <select name="status" id="status" class="form-control">
                                <option value="{{App\Models\User::STATUS_ACTIVE}}" @if( $user->status == App\Models\User::STATUS_ACTIVE ) selected @endif>Active</option>
                                <option value="{{App\Models\User::STATUS_INACTIVE}}" @if( $user->status == App\Models\User::STATUS_INACTIVE ) selected @endif>Inactive</option>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-sm-offset-2 col-sm-10">
                        <label>
                            Note: Status will be checked when user login.
                        </label>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-sm-offset-2 col-sm-10">
                        <button type="submit" class="btn btn-danger">Submit</button>
                    </div>
                </div>
            </form>
        
        </div>
    </x-panel-card>
    @endif

</div>

@endsection


@push('scripts')

<script>

$(document).ready(function() {


});

</script>

@endpush