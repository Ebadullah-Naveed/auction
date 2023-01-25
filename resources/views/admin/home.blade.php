@extends('layouts.admin.app', [ 'page' => 'dashboard', 'title'=>'Home'])

@push('css')

@endpush

@section('content')

<div class="row">
    
    <div class="col-lg-3">
        <div class="counter-box p-40 white shadow2 r-5">
            <div class="float-right">
                <span class="icon icon-people_outline s-48"></span>
            </div>
            <div class="sc-counter s-36">{{$total_users}}</div>
            <h6 class="counter-title">Total Users</h6>
        </div>
    </div>
    
    
</div>

@endsection

@push('scripts')

@endpush