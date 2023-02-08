@extends('layouts.admin.app', [ 'page' => 'user_management', 'title'=> $title ])

@push('css')

@endpush

@section('content')

<div class="row row-eq-height my-3">

    <x-panel-card class="" >
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
            <dd>{{$user->m_created_at}}</dd>

            {{-- <dt>NIC image</dt>
            <dd><img src="{{$user->cnic_front_image}}" alt="" width=200px>
            <img src="{{$user->cnic_back_image}}" alt="" width=200px></dd> --}}

            <dt>Status</dt>
            <dd>{!! $user->getStatusHtml() !!}</dd>

        </dl>
        
        <x-slot name="footer">
            <div class="d-flex justify-content-start">
                <a href="{{$action_url}}" class="btn btn-primary btn-sm">Go Back</a>
            </div>
        </x-slot>

    </x-panel-card>

</div>

@endsection


@push('scripts')

<script>

$(document).ready(function() {


});

</script>

@endpush