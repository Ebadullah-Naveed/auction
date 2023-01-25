@extends('layouts.admin.app', [ 'page' => 'user', 'title'=> $title ])

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
                <label class="col-form-label s-12">Phone Verification</label>
                <select class="form-control r-0 light s-12 filter_dropdown" id="isPhoneVerified">
                    <option value="">All</option>
                    <option value="1">Verified</option>
                    <option value="0">Not Verified</option>
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
                    <th class="no-sort">Name</th>
                    <th class="no-sort">Email</th>
                    <th class="no-sort">Mobile Number</th>
                    <th class="no-sort">OTP</th>
                    {{-- <th class="no-sort">Date Joined</th> --}}
                    <th class="no-sort">Last Login</th>
                    <th class="no-sort">Goals</th>
                    {{-- <th>Roundup</th> --}}
                    <th>Phone Verified</th>
                    <th>Email Verified</th>
                    <th>KYC</th>
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
    const isPhoneVerifiedId = '#isPhoneVerified';
    const isEmailVerifiedId = '#isEmailVerified';

    const private = (function() {

        const renderListingTable = function() {
            if ($.fn.DataTable.isDataTable(listingTableId)) $(listingTableId).DataTable().destroy();

            let url = "{{$listing_fetch_url}}";
            let status = $(statusId).val();
            let is_phone_verified = $(isPhoneVerifiedId).val();
            let is_email_verified = $(isEmailVerifiedId).val();

            const params = {
                "_token": "{{ csrf_token() }}",
                status, is_phone_verified, is_email_verified
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
                    "targets": [6,7,8,9,10,11],
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
                        render: function(data,type,row) {
                            return row.user_document?row.user_document.full_name:'-';
                        }
                    },
                    {
                        render: function(data,type,row) {
                            if( row.user_document && (row.user_document.email!=null) && (row.user_document.email!='') ){
                                let email = row.user_document.email;
                                let emailTruncated = email.substring(0, 16)+(email.length>16?'...':'');
                                return `<span title="${email}">${emailTruncated}</span>`;
                            }
                            return '';
                        }
                    },
                    { data: 'phone_number' },
                    { data: 'otp' },
                    // { data: 'date_joined',
                    //     render: function(data,type,row){
                    //         return row.m_date_joined;
                    //     }
                    // },
                    {
                        render: function( data, type, row ) {
                            return `${row.last_login??'-'} <br> ${row.last_login_ip??''}`;
                        }
                    },
                    {   
                        render: function(data, type, row) {
                            return `${row.total_goal_html}`;
                        }
                    },
                    // {   
                    //     render: function(data, type, row) {
                    //         return `${row.roundup_status_html}`;
                    //     }
                    // },
                    {   
                        render: function(data, type, row) {
                            return `${row.is_phone_verified_html}`;
                        }
                    },
                    {   
                        render: function(data, type, row) {
                            return `${row.is_email_verified_html}`;
                        }
                    },
                    {   
                        render: function(data, type, row) {
                            return `${row.is_kyc_completed_html}`;
                        }
                    },
                    {   
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