@extends('layouts.admin.app', [ 'page' => 'cronjob', 'title'=> $title ])

@push('css')

@endpush

@section('content')

<div class="row row-eq-height my-3">

    <x-panel-card class="col-md-12 mx-auto" >
        <x-slot name="title">
            {{$title}}
        </x-slot>
        
        <table class="table table-borderedx datatable-nowrap">
            <tr>
                <th width="2%">#</th>
                <th width="30%">Name</th>
                <th width="10%">Action</th>
                <th width="58%">Description</th>
            </tr>
            <tr>
                <td>1</td>
                <td>AMC Account Inquiry job</td>
                <td>
                    <a href="{{route('cron.amc.account.status.sync')}}" class="btn btn-primary btn-sm" target="_blank"> <i class="icon icon-link pr-1"></i> Execute </a>
                </td>
                <td>
                    This job will sync new account creation status from AMC.
                </td>
            </tr>
            <tr>
                <td>2</td>
                <td>AMC Buying Transaction Sync</td>
                <td>
                    <a href="{{route('cron.amc.transaction.buying')}}" class="btn btn-primary btn-sm" target="_blank"> <i class="icon icon-link pr-1"></i> Execute </a>
                </td>
                <td>
                    This job will sync in-processing buying transactions from AMC.
                </td>
            </tr>
            <tr>
                <td>3</td>
                <td>AMC Selling Transaction Sync</td>
                <td>
                    <a href="{{route('cron.amc.transaction.selling')}}" class="btn btn-primary btn-sm" target="_blank"> <i class="icon icon-link pr-1"></i> Execute </a>
                </td>
                <td>
                    This job will sync in-processing selling transactions from AMC.
                </td>
            </tr>
            <tr>
                <td>4</td>
                <td>Daily Fund Nav Value Sync</td>
                <td>
                    <a href="{{route('cron.fund.nav')}}" class="btn btn-primary btn-sm" target="_blank"> <i class="icon icon-link pr-1"></i> Execute </a>
                </td>
                <td>
                    This job will sync daily NAV value from AMC.
                </td>
            </tr>
            <tr>
                <td>5</td>
                <td>Current Nav Sync (Dependent on Daily Fund Nav Job)</td>
                <td>
                    <a href="{{route('cron.current.nav.update')}}" class="btn btn-primary btn-sm" target="_blank"> <i class="icon icon-link pr-1"></i> Execute </a>
                </td>
                <td>
                    This job will sync NAV value in user goal, asset allocation and accounts table.
                </td>
            </tr>
            <tr>
                <td>6</td>
                <td>Process Recurring Payments (Goal Scheduled Payments)</td>
                <td>
                    <a href="{{route('cron.scheduled.payments.process')}}" class="btn btn-primary btn-sm" target="_blank"> <i class="icon icon-link pr-1"></i> Execute </a>
                </td>
                <td>
                    This job will process goal scheduled payments (current date) and send investment details to AMC.
                </td>
            </tr>
            
        </table>
            
    </x-panel-card>

</div>


@endsection

@push('scripts')

@endpush