@extends('layouts.admin.app', [ 'page' => 'user_management', 'title'=> $title ])

@push('css')

@endpush

@section('content')

<div class="row row-eq-height my-3">

    @if( App\Models\User::canAdd() )
        <div class="col-md-12">
            <a href="{{$add_url}}" class="btn btn-primary btn-sm mb-3"><i class="icon icon-plus pr-0"></i> Add New User </a>
        </div>
    @endif

    <x-panel-card filter-btn="show">
        <x-slot name="title">
            {{$title}}
        </x-slot>

        <div class="row pb-4 collapse filter_collapse_box" id="filterCollapseBox">
            
            <div class="col-md-4 col-lg-3">
                <label class="col-form-label s-12">Role</label>
                <select class="form-control r-0 light s-12 filter_dropdown" id="role" >
                    <option value="">All</option>
                    @foreach($roles as $role)
                        <option value="{{$role->id}}">{{ucfirst($role->name)}}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-4 col-lg-3">
                <label class="col-form-label s-12">Status</label>
                <select class="form-control r-0 light s-12 filter_dropdown" id="status">
                    <option value="">All</option>
                    <option value="1">Active</option>
                    <option value="0">Inactive</option>
                </select>
            </div>
            
            <div class="col-md-6 col-lg-6">
                <label class="col-form-label s-12">Action</label> <br>

                <button class="btn btn-sm btn-primary submit_filter"> Submit </button>
                <button class="btn btn-sm btn-primary clear_filter ml-2"> Clear </button>
                
            </div>

        </div>
        
        <table id="listingTable" class="table table-bordered p-0">
            <thead>
                <tr>
                    <th style="width: 1%">#</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th class="no-sort">Role</th>
                    <th class="no-sort">Last Login</th>
                    <th>Status</th>
                    <th class="no-sort" style="width: 100px">Action</th>
                </tr>
            </thead>
            <tbody>

            </tbody>
        </table>

    </x-panel-card>

</div>

@endsection

@push('scripts')

<script>

if (!myApp) {
    var myApp = {};
}

myApp.listing = (function() {

    /**
     * ------------------------------------------------------------------------
     * Classes
     * ------------------------------------------------------------------------
     */
    const submitFilterBtnClass = '.submit_filter';
    const clearFilterBtnClass = '.clear_filter';

    /**
     * ------------------------------------------------------------------------
     * Ids
     * ------------------------------------------------------------------------
     */
    const listingTableId = '#listingTable';
    const statusId = '#status';
    const roleId = '#role';

    const private = (function() {

        const renderListingTable = function() {
            if ($.fn.DataTable.isDataTable(listingTableId)) $(listingTableId).DataTable().destroy();

            let url = "{{$listing_fetch_url}}";
            let role = $(roleId).val();
            let status = $(statusId).val();

            const params = {
                "_token": "{{ csrf_token() }}",
                status: status,
                role: role
            }
            
            $(listingTableId).DataTable({
                language: { "processing": datatablesLoaderHtml },
                columnDefs: [ {
                    "targets"  : 'no-sort',
                    "orderable": false,
                    "order": []
                },
                {
                    "targets": [5],
                    "className": "text-center"
                }],
                "order": [[ 0, "desc" ]],
                "scrollX": true,
                processing: true,
                serverSide: true,
                ajax: {
                    url: url,
                    type: "POST",
                    data: params
                },
                columns: [
                    { data: 'id' },  
                    { data: 'name' },
                    { data: 'email' },
                    {
                        data: 'role_id',
                        render: function( data, type, row ) {
                            return `${row.role.name}`;
                        }
                    },
                    {
                        render: function( data, type, row ) {
                            return `${row.last_login??'-'} <br> ${row.last_login_ip??''}`;
                        }
                    },
                    {   
                        render: function(data, type, row) {
                            return `${row.status_html}`;
                        }
                    },
                    {   
                        render: function(data, type, row) {
                            return `${row.view_btn} ${row.edit_btn}`;
                        }
                    },
                                
                ],
                "drawCallback": function( settings ) {

                } //end drawcallback
            });
            
        };

        return {
            renderListingTable,
            
        }
    })();

    const binding = function() {

        $(submitFilterBtnClass).click(function(){
            private.renderListingTable();
        });

        $(clearFilterBtnClass).click(function(){
            $('input').val('');
            $('select').val('');
            private.renderListingTable();
        });

    };

    const init = function() {
        binding();
        private.renderListingTable();
    };

    return {
        init,
    }
})();


$(document).ready(function() {
    myApp.listing.init();
});

</script>
@endpush