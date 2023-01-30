@extends('layouts.admin.app', [ 'page' => 'product_management', 'title'=> $title ])

@push('css')

@endpush

@section('content')

<div class="row row-eq-height my-3">

    <x-panel-card class="" >
        <x-slot name="title">
            {{$title}}
        </x-slot>
        
        <div class="">
            @csrf

            <div class="form-row">
                <div class="form-group col-8 mb-2">
                    <label for="name" class="w-100 text-left">Name</label>
                    @php
                    // dd($product);
                    @endphp
                    <input type="text" class="form-control" value="{{$product->name}}" disabled>
                </div>
                <div class="form-group col-4 mb-2">
                    <label for="name" class="w-100 text-left">Category </label>
                    <input type="text" class="form-control" value="{{ucfirst($product->category->name)}}" disabled>
                </div>

                <div class="form-group col-4 mb-2">
                    <label for="price" class="w-100 text-left">Price (Rs) </label>
                    <input type="text" class="form-control" value="{{$product->price}}" disabled>
                </div>
                <div class="form-group col-4 mb-2">
                    <label for="min_increment" class="w-100 text-left">Min Increment (Rs) </label>
                    <input type="text" class="form-control" value="{{$product->min_increment}}" disabled>
                    
                </div>
                <div class="form-group col-4 mb-2">
                    <label for="max_increment" class="w-100 text-left">Max Increment (Rs) (0 = No Max Limit) </label>
                    <input type="text" class="form-control" value="{{$product->max_increment}}" disabled>
                </div>

                <div class="form-group col-12 mb-2">
                    <label for="end_datetime" class="w-100 text-left">End Datetime </label>
                    <input type="text" class="form-control" value="{{$product->m_end_datetime}}" disabled>
                </div>

                <div class="col-12">
                    <hr>
                    <h5 class="mb-2"> Attributes </h5>
                </div>

                @php
                $productAttributes = $product->attributes;   
                $arrangedAttributes = [];
                foreach ($product->attributes as $key => $prodAttr) {
                    $arrangedAttributes[$prodAttr->key] = $prodAttr->value;
                }
                @endphp

                @foreach( $product->category->m_attribute_json as $attr )
                    <div class="form-group col-6 mb-2">
                        <input type="hidden" name="product_attributes_label[{{$attr->key}}]" value="{{$attr->label}}">
                        <label for="end_datetime" class="w-100 text-left">{{$attr->label}}</label>
                        <input type="text" class="form-control" 
                            name="product_attributes[{{$attr->key}}]"
                            value="{{($product&&(isset($arrangedAttributes[$attr->key])))?$arrangedAttributes[$attr->key]:''}}" 
                            disabled
                        >
                    </div>
                @endforeach
                
            </div>

            {{-- <div>
                <a href="{{$action_url}}" class="btn btn-primary btn-sm" type="button">Go Back</a>
            </div> --}}

        </div>

    </x-panel-card>

    @if($product)
    <x-panel-card class="col-md-12 mt-4 mb-5" filter-btn="show">
        <x-slot name="title">
            Bids
        </x-slot>

        <div class="row pb-4 collapse filter_collapse_box" id="filterCollapseBox">
            
            {{-- <div class="col-md-4 col-lg-3">
                <label class="col-form-label s-12">Status</label>
                <select class="form-control r-0 light s-12 filter_dropdown" id="status">
                    <option value="">All</option>
                    <option value="1">Active</option>
                    <option value="0">Inactive</option>
                </select>
            </div> --}}
            
            <div class="col-md-4 col-lg-3">
                <label class="col-form-label s-12">Date From</label>
                <input type="date" id="dateFrom" class="form-control r-0 light s-12">
            </div>

            <div class="col-md-4 col-lg-3">
                <label class="col-form-label s-12">Date To</label>
                <input type="date" id="dateTo" class="form-control r-0 light s-12">
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
                    <th class="no-sort">User</th>
                    <th>Bid Amount</th>
                    <th class="no-sort">Timestamp</th>
                </tr>
            </thead>
            <tbody>

            </tbody>
        </table>

    </x-panel-card>
    @endif

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
        // const statusId = '#status';
        const dateFromId = '#dateFrom'
        const dateToId = '#dateTo'
    
        const private = (function() {
    
            const renderListingTable = function() {
                if ($.fn.DataTable.isDataTable(listingTableId)) $(listingTableId).DataTable().destroy();
    
                let url = "{{$product_bid_fetch}}";
                // let status = $(statusId).val();
                let date_from = $(dateFromId).val();
                let date_to = $(dateToId).val();
    
                const params = {
                    "_token": "{{ csrf_token() }}",
                    date_from, date_to,
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
                        {   
                            render: function(data, type, row) {
                                return `${row.user.name} (${row.user.email})`;
                            }
                        },
                        {   
                            data: 'amount',
                            render: function(data, type, row) {
                                return `${row.m_amount}`;
                            }
                        },
                        {   
                            data: 'created_at',
                            render: function(data, type, row) {
                                return `${row.m_created_at}`;
                            }
                        }
                                    
                    ],
                    "drawCallback": function( settings ) {
                        // $(listingTableId).parent().addClass('col-datatable-overflow-x-scroll');
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