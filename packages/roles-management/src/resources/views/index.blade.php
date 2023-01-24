@extends('layouts.admin.app', [ 'page' => 'role', 'title'=> $title ])

@push('css')

@endpush

@section('content')

<div class="row row-eq-height my-3">

    @if( App\Models\Role::canAdd() )
        <div class="col-md-12">
            <a href="{{$add_url}}" class="btn btn-primary btn-sm mb-3"><i class="icon icon-plus pr-0"></i> Add New Role </a>
        </div>
    @endif

    <x-panel-card >
        <x-slot name="title">
            {{$title}}
        </x-slot>
        
        <table id="listingTable" class="table table-bordered">
            <tbody>
                <tr>
                    <th style="width: 50px"> &nbsp;&nbsp;#</th>
                    <th>Name</th>
                    <th>Action</th>
                </tr>
                @foreach ($roles as $key=>$role)
                    <tr>
                        <td align="middle">{{$key+1}}</td>
                        <td>{{$role->name}}</td>
                        <td>
                            {!!$role->getEditBtnHtml()!!}
                            {!!$role->getDeleteBtnHtml()!!}
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

    </x-panel-card>

</div>

@endsection

@push('scripts')

@endpush