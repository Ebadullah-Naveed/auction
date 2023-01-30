@extends('layouts.admin.app', [ 'page' => 'product_management', 'title'=> $title ])

@push('css')

@endpush

@section('content')

<div class="row row-eq-height my-3">

    @if( App\Models\Product::canAdd() )
        <div class="col-md-12">
            @foreach( $category as $cat )
                <a href="{{route('admin.product.create',['category_id'=>$cat->id])}}" class="btn btn-primary btn-sm mb-3"><i class="icon icon-plus pr-0"></i> Add {{ucfirst($cat->name)}} </a>
            @endforeach
        </div>
    @endif

    <x-panel-card filter-btn="show">
        <x-slot name="title">
            {{$title}}
        </x-slot>

        <div class="row pb-4 collapse filter_collapse_box" id="filterCollapseBox">
            
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
        
        <table id="listingTable" class="table table-bordered p-0 datatable-nowrap">
            <thead>
                <tr>
                    <th style="width: 1%">#</th>
                    <th>Name</th>
                    <th>Price</th>
                    <th>Min Increment</th>
                    <th>Max Increment</th>
                    <th class="no-sort">End Datetime</th>
                    <th class="no-sort">Total Bids</th>
                    <th class="no-sort">Last Bid</th>
                    <th class="no-sort">Created At</th>
                    <th class="no-sort">Can Bid</th>
                    <th class="no-sort">Status</th>
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

    const private = (function() {

        const renderListingTable = function() {
            if ($.fn.DataTable.isDataTable(listingTableId)) $(listingTableId).DataTable().destroy();

            let url = "{{$listing_fetch_url}}";
            let status = $(statusId).val();

            const params = {
                "_token": "{{ csrf_token() }}",
                status: status,
            }
            
            $(listingTableId).DataTable({
                language: { "processing": datatablesLoaderHtml },
                columnDefs: [ {
                    "targets"  : 'no-sort',
                    "orderable": false,
                    "order": []
                },
                // {
                //     "targets": [5],
                //     "className": "text-center"
                // }
                ],
                "order": [[ 0, "desc" ]],
                // "scrollX": true,
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
                    {   
                        data: 'price',
                        render: function(data, type, row) {
                            return `${row.m_price}`;
                        }
                    },
                    {   
                        data: 'min_increment',
                        render: function(data, type, row) {
                            return `${row.m_min_increment}`;
                        }
                    },
                    {   
                        data: 'max_increment',
                        render: function(data, type, row) {
                            return `${row.m_max_increment}`;
                        }
                    },
                    {   
                        render: function(data, type, row) {
                            return `${row.m_end_datetime}`;
                        }
                    },
                    {   
                        render: function(data, type, row) {
                            return `${row.bids_count}`;
                        }
                    },
                    {   
                        render: function(data, type, row) {
                            return `${row.last_bid_html}`;
                        }
                    },
                    {   
                        data: 'created_at',
                        render: function(data, type, row) {
                            return `${row.m_created_at}`;
                        }
                    },
                    {   
                        render: function(data, type, row) {
                            return `${row.can_bid_html}`;
                        }
                    },
                    {   
                        render: function(data, type, row) {
                            return `${row.status_html}`;
                        }
                    },
                    {   
                        render: function(data, type, row) {
                            return `${row.edit_btn}`;
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