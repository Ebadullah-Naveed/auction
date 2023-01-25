@extends('layouts.admin.app', [ 'page' => 'activity_log', 'title'=> $title ])

@push('css')
<style>
    
</style>
@endpush

@section('content')

<div class="row row-eq-height my-3">

    <x-panel-card filter-btn="show">
        <x-slot name="title">
            {{$title}}
        </x-slot>

        <div class="row pb-4 collapse filter_collapse_box" id="filterCollapseBox">

            <div class="col-md-4 col-lg-3">
                <label class="col-form-label s-12">Date From</label>
                <input type="date" id="dateFrom" class="form-control r-0 light s-12">
            </div>

            <div class="col-md-4 col-lg-3">
                <label class="col-form-label s-12">Date To</label>
                <input type="date" id="dateTo" class="form-control r-0 light s-12">
            </div>
            
            <div class="col-md-4 col-lg-3">
                <label class="col-form-label s-12">Type</label>
                <select class="form-control r-0 light s-12 filter_dropdown" id="type" >
                    <option value="">All</option>
                    @foreach($templates as $template)
                        <option value="{{$template->id}}">{{ucfirst(str_replace("_"," ",$template->activity_name))}}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-3 col-lg-3">
                <label class="col-form-label s-12">Action</label> <br>

                <button class="btn btn-sm btn-primary submit_filter"> Submit </button>
                <button class="btn btn-sm btn-primary clear_filter ml-2"> Clear </button>
                
            </div>

        </div>
        
        <table id="listingTable" class="table table-bordered p-0">
            <thead>
                <tr>
                    <th style="width: 1%">#</th>
                    <th class="no-sort">Description</th>
                    <th class="no-sort">Timestamp</th>
                    <th class="no-sort">Json Params</th>
                </tr>
            </thead>
            <tbody>

            </tbody>
        </table>

    </x-panel-card>

</div>

@endsection

@section('modals')

<!-- Modal -->
<div class="modal fade" id="activityLogDetailModal" tabindex="-1" role="dialog" aria-labelledby="activityLogDetailModal" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Activity Log Details</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-row">
                    <div class="form-group col-12 m-0 mb-2">
                        <label for="role" class="col-form-label s-12">Admin Id</label>
                        <input type="text" id="modalUserId" class="form-control" readOnly="true">
                    </div>
                    <div class="form-group col-12 m-0 mb-2">
                        <label for="role" class="col-form-label s-12">Description</label>
                        <input type="text" id="modalDescription" class="form-control" readOnly="true">
                    </div>
                    <div class="form-group col-12 m-0 mb-2">
                        <label for="role" class="col-form-label s-12">Timestamp</label>
                        <input type="text" id="modalTime" class="form-control" readOnly="true">
                    </div>
                    <div class="form-group col-12 m-0 mb-2">
                        <label for="role" class="col-form-label s-12">Json Params</label>
                        <textarea id="modalJsonParam" cols="30" rows="15" class="form-control" readOnly="true"></textarea>
                    </div>
                    <div class="form-group col-12 m-0">
                        
                    </div>
                </div>
            </div>
        </div>
    </div>
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
    const cellDataClass = '.cell_data';

    /**
     * ------------------------------------------------------------------------
     * Ids
     * ------------------------------------------------------------------------
     */
    const listingTableId = '#listingTable';
    const typeId = '#type';
    const activityLogDetailModalId = '#activityLogDetailModal';
    const modalJsonParamId = '#modalJsonParam';
    const modalDescriptionId = '#modalDescription';
    const modalTimeId = '#modalTime';
    const modalUserIdId = '#modalUserId';
    const dateFromId = '#dateFrom'
    const dateToId = '#dateTo'


    const private = (function() {

        const renderListingTable = function() {
            if ($.fn.DataTable.isDataTable(listingTableId)) $(listingTableId).DataTable().destroy();

            let url = "{{$listing_fetch_url}}";
            let type = $(typeId).val();
            let date_from = $(dateFromId).val();
            let date_to = $(dateToId).val();

            const params = {
                "_token": "{{ csrf_token() }}",
                type: type,
                date_from: date_from,
                date_to: date_to,
            }
            
            $(listingTableId).DataTable({
                // language: { "processing": datatablesLoaderHtml },
                columnDefs: [ {
                    "targets"  : 'no-sort',
                    "orderable": false,
                    "order": []
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
                    { data: 'text' },
                    { data: 'activity_time' },
                    {   
                        render: function(data, type, row) {
                            return `<span class="cell_data" data-id="${row.id}" >${row.json_params.substring(0,50)}</span>`;
                        }
                    },
                                
                ],
                "drawCallback": function( settings ) {
                    $(`${listingTableId} tbody tr`).on('click', function() {

                        let id = $(this).find(cellDataClass).data('id');
                        showLogDetailModal(id);

                    });
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

    /**
     * Function to get log details and open modal
     */
     const showLogDetailModal = (id) => {
        const url = '{{$detail_fetch_url}}';
        const data = {
            _token: '{{csrf_token()}}',
            id:id
        };
        
        showLoader();
        $.post(url, data, function(response) {
            if(response.status == true) {

                let user_id = response.data.user_id;
                let desc = response.data.description;
                let time = response.data.activity_time;
                let json_params = response.data.json_params;
                $(modalUserIdId).val(user_id);
                $(modalDescriptionId).val(desc);
                $(modalTimeId).val(time);
                $(modalJsonParamId).val((json_params));
                // Format Text area json
                prettyPrint(modalJsonParamId);
                $(activityLogDetailModalId).modal('show');

            } else {
                Toast.fire({ title: 'Error', text: response.message, type: "error" });
            }
            hideLoader();
        });
    }

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