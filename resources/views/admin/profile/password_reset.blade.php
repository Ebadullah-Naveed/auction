@extends('layouts.admin.app', [ 'page' => '', 'title'=> $title ])

@push('css')

@endpush

@section('content')

<div class="row row-eq-height my-3">

    <x-panel-card class="col-md-8 mx-auto" >
        <x-slot name="title">
            {{$title}}
        </x-slot>
        
        <form action="{{$action_url}}" method="POST" class="needs-validation @if($errors->any()) was-validated @endif" novalidate>
            @csrf
            <div class="form-row">

                <div class="form-group col-md-12 mb-3">
                    <label for="name" class="w-100 text-left">Current Password <span class="text-danger">*</span> </label>
                    <input type="password" class="form-control" name="current_password" placeholder="Enter old password" required>
                    @error('current_password')
                        <div class="invalid-feedback"> {{$message}}</div>
                    @enderror
                </div>

                <div class="form-group col-md-12 mb-3">
                    <label for="name" class="w-100 text-left">New Password <span class="text-danger">*</span> </label>
                    <input type="password" class="form-control" name="password" placeholder="Enter new password" required>
                    @error('password')
                        <div class="invalid-feedback"> {{$message}} </div>
                    @enderror
                </div>

                <div class="form-group col-md-12 mb-3">
                    <label for="name" class="w-100 text-left">Confirm Password <span class="text-danger">*</span> </label>
                    <input type="password" class="form-control" name="password_confirmation" placeholder="confirm new password" required>
                    @error('password_confirmation')
                    <div class="invalid-feedback"> {{$message}} </div>
                @enderror
                </div>
              
            </div>

            <div>
                <button class="btn btn-primary" type="submit">Submit</button>
            </div>

        </form>

    </x-panel-card>

</div>


@endsection

@push('scripts')

@endpush