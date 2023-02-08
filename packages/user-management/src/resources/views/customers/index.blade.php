@extends('layouts.admin.app', [ 'page' => 'customer_management', 'title'=> $title ])

@push('css')

@endpush

@section('content')

<div class="row row-eq-height my-3">

    <x-panel-card filter-btn="show">
        <x-slot name="title">
            {{$title}}
        </x-slot>

        <div class="row pb-4 pl-3x collapse filter_collapse_box" id="filterCollapseBox">
            
            <div class="col-md-4 col-lg-3">
                <label class="col-form-label s-12">Status</label>
                <select class="form-control r-0 light s-12 filter_dropdown" id="status">
                    <option value="">All</option>
                    <option value="1">Active</option>
                    <option value="0">Inactive</option>
                </select>
            </div>

            <div class="col-md-4 col-lg-3">
                <label class="col-form-label s-12">Email Verification</label>
                <select class="form-control r-0 light s-12 filter_dropdown" id="isEmailVerified">
                    <option value="">All</option>
                    <option value="1">Verified</option>
                    <option value="0">Not Verified</option>
                </select>
            </div>
            
            <div class="col-md-6 col-lg-6">
                <label class="col-form-label s-12">Action</label> <br>

                <button class="btn btn-sm btn-primary submit_filter"> Submit </button>
                <button class="btn btn-sm btn-primary clear_filter ml-2"> Clear </button>
                
            </div>

        </div>
        
        <table id="listingTable" class="table table-bordered p-0 datatable-nowrap">
            <thead>
                <tr>
                    <th style="width: 1%">#</th>
                    <th xclass="no-sort">Name</th>
                    <th xclass="no-sort">Email</th>
                    <th xclass="no-sort">Phone Number</th>
                    <th xclass="no-sort">Username</th>
                    <th class="no-sort">CNIC</th>
                    <th class="no-sort">OTP</th>
                    <th class="no-sort">Last Login</th>
                    <th>Email Verified</th>
                    <th>Status</th>
                    <th style="width: 10px">Action</th>
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
    const isEmailVerifiedId = '#isEmailVerified';

    const private = (function() {

        const renderListingTable = function() {
            if ($.fn.DataTable.isDataTable(listingTableId)) $(listingTableId).DataTable().destroy();

            let url = "{{$listing_fetch_url}}";
            let status = $(statusId).val();
            let is_email_verified = $(isEmailVerifiedId).val();

            const params = {
                "_token": "{{ csrf_token() }}",
                status, is_email_verified
            }
            
            $(listingTableId).DataTable({
                language: { "processing": datatablesLoaderHtml },
                // "order": [[ 0, "desc" ]],
                columnDefs: [ {
                    "targets"  : 'no-sort',
                    "orderable": false,
                    "order": []
                },
                {
                    "targets": [0,7,8,9,10],
                    "className": "text-center"
                }],
                // "scrollX": true,
                processing: true,
                serverSide: true,
                ajax: {
                    url: url,
                    type: "POST",
                    data: params
                },
                columns: [
                    { 
                        render: function(data,type,row) {
                            return row.DT_RowIndex;
                        }
                    },  
                    {
                        data: 'name',
                        render: function(data,type,row) {
                            return row.name;
                        }
                    },
                    {
                        data: 'email',
                        render: function(data,type,row) {
                            return row.email;
                        }
                    },
                    {
                        data: 'phone_number',
                        render: function(data,type,row) {
                            return row.phone_number;
                        }
                    },
                    {
                        data: 'username',
                        render: function(data,type,row) {
                            return row.username;
                        }
                    },
                    {
                        data: 'cnic',
                        render: function(data,type,row) {
                            return row.cnic;
                        }
                    },
                    { data: 'otp' },
                    {
                        render: function( data, type, row ) {
                            return `${row.last_login??'-'} <br> ${row.last_login_ip??''}`;
                        }
                    },
                    {   
                        data: 'is_email_verified',
                        render: function(data, type, row) {
                            return `${row.is_email_verified_html}`;
                        }
                    },
                    {   
                        data: 'status',
                        render: function(data, type, row) {
                            return `${row.status_html}`;
                        }
                    },
                    { 
                        render: function(data, type, row) {
                            return `${row.view_btn}`;
                        }
                    },
                                
                ],
                "drawCallback": function( settings ) {
                    $(listingTableId).parent().addClass('col-datatable-overflow-x-scroll');
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