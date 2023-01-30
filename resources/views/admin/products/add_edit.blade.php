@extends('layouts.admin.app', [ 'page' => 'product_management', 'title'=> $title ])

@push('css')

@endpush

@section('content')

<div class="row row-eq-height my-3">

    <x-panel-card class="" >
        <x-slot name="title">
            {{$title}}
        </x-slot>
        
        <form action="{{$action_url}}" method="POST" enctype="multipart/form-data" class="needs-validation @if($errors->any()) was-validated @endif" novalidate>
            @csrf

            {{-- <input type="hidden" name="{{$product?'updated_by_id':'created_by_id'}}" value="{{auth()->user()->id}}"> --}}
            {{-- @if($errors->any())
                {{ implode('', $errors->all('<div>:message</div>')) }}
            @endif --}}
            <div class="form-row">
                <input type="hidden" name="product_id" value="{{$product?$product->id:''}}" >
                <input type="hidden" name="category_id" value="{{$category->id}}" >
                <div class="form-group col-8 mb-2">
                    <label for="name" class="w-100 text-left">Name <span class="text-danger">*</span> </label>
                    <input type="text" class="form-control" name="name" value="{{$product?$product->name:old('name')}}" required>
                    @error('name')
                        <div class="invalid-feedback"> {{$message}} </div>
                    @enderror
                </div>
                <div class="form-group col-4 mb-2">
                    <label for="name" class="w-100 text-left">Category </label>
                    <input type="text" class="form-control" value="{{ucfirst($category->name)}}" disabled>
                </div>

                <div class="form-group col-4 mb-2">
                    <label for="price" class="w-100 text-left">Price (Rs) <span class="text-danger">*</span> </label>
                    <input type="text" class="form-control" name="price" value="{{$product?$product->price:old('price')}}" required>
                    @error('price')
                        <div class="invalid-feedback"> {{$message}} </div>
                    @enderror
                </div>
                <div class="form-group col-4 mb-2">
                    <label for="min_increment" class="w-100 text-left">Min Increment (Rs) <span class="text-danger">*</span> </label>
                    <input type="text" class="form-control" name="min_increment" value="{{$product?$product->min_increment:old('min_increment')}}" required>
                    @error('min_increment')
                        <div class="invalid-feedback"> {{$message}} </div>
                    @enderror
                </div>
                <div class="form-group col-4 mb-2">
                    <label for="max_increment" class="w-100 text-left">Max Increment (Rs) (0 = No Max Limit) <span class="text-danger">*</span> </label>
                    <input type="text" class="form-control" name="max_increment" value="{{$product?$product->max_increment:old('max_increment')}}" required>
                    @error('max_increment')
                        <div class="invalid-feedback"> {{$message}} </div>
                    @enderror
                </div>

                <div class="form-group col-12 mb-2">
                    <label for="end_datetime" class="w-100 text-left">End Datetime <span class="text-danger">*</span> </label>
                    <input type="text" class="form-control date-time-picker" name="end_datetime" value="{{$product? Carbon\Carbon::parse($product->end_datetime)->isoFormat('YYYY/MM/DD HH:mm'):old('end_datetime')}}" required>
                    @error('end_datetime')
                        <div class="invalid-feedback"> {{$message}} </div>
                    @enderror
                </div>

                <div class="col-12">
                    <hr>
                    <h5 class="mb-2"> Attributes </h5>
                </div>

                @php
                if(isset($product->attributes))
                {
                    $arrangedAttributes = [];
                    foreach ($product->attributes as $key => $prodAttr) {
                        $arrangedAttributes[$prodAttr->key] = $prodAttr->value;
                    }
                }
                @endphp

                @foreach( $category->m_attribute_json as $attr )
                    <div class="form-group col-6 mb-2">
                        <input type="hidden" name="product_attributes_label[{{$attr->key}}]" value="{{$attr->label}}">
                        <label for="end_datetime" class="w-100 text-left">{{$attr->label}}  {!!($attr->required==1)?'<span class="text-danger">*</span>':''!!} </label>
                        <input type="text" class="form-control" 
                            name="product_attributes[{{$attr->key}}]"
                            value="{{($product&&(isset($arrangedAttributes[$attr->key])))?$arrangedAttributes[$attr->key]:''}}" 
                            {{($attr->required==1)?'required':''}}
                        >
                    </div>
                @endforeach

                <div class="form-group col-6 mb-2">
                    <label for="short_desc" class="w-100 text-left">Short Description </label>
                    <textarea class="form-control" name="short_desc" cols="10" rows="3">{{$product?$product->short_desc:old('short_desc')}}</textarea>
                    @error('short_desc')
                        <div class="invalid-feedback"> {{$message}} </div>
                    @enderror
                </div>

                <div class="form-group col-6 mb-2">
                    <label for="terms" class="w-100 text-left">Terms & Conditions </label>
                    <textarea class="form-control" name="terms" cols="10" rows="3">{{$product?$product->terms:old('terms')}}</textarea>
                    @error('terms')
                        <div class="invalid-feedback"> {{$message}} </div>
                    @enderror
                </div>

                <div class="form-group col-12 mb-2">
                    <label for="image" class="w-100 text-left">Image</label>
                    <input type="file" class="form-control dropify" name="image" id="productImage" data-allowed-file-extensions="jpg png jpeg" data-max-file-size="1M">
                    @error('image')
                        <div class="invalid-feedback"> {{$message}} </div>
                    @enderror
                </div>

                <div class="form-group col-12 my-2 mb-4 pl-2">
                    <label for="status" class="xcol-form-label s-12 mr-4">Status</label>
                    <div class="d-flex justify-content-left align-items-center">
                        <div class="material-switch">
                            <input id="statusOption" name="status" type="checkbox"  {{($product && ($product->status==App\Models\Category::STATUS_ACTIVE))?'checked':''}} >
                            <label for="statusOption" class="bg-success"></label>
                        </div>
                    </div>
                </div>
                
            </div>

            <div>
                <button class="btn btn-primary" type="submit">Submit</button>
            </div>

        </form>

    </x-panel-card>

    @if($product)
    <x-panel-card class="col-md-12 mt-4 mb-5" >
        <x-slot name="title">
            Product Media
        </x-slot>
        
        <form id="image_sequence_form" method="POST" action="{{route('product.image.update.sequence',['id'=>$product->id])}}">
            @csrf
            @if( count($product->images_by_sequence)>0 )
            <div class="box-header with-border">
                <div class="row pl-3 justify-content-start">
                    <input type="submit" form="image_sequence_form" class="btn btn-sm mb-3 btn-success"
                        id="image_sequence_form_submit"
                        value="Update sequence">
                    {{-- </button> --}}
                </div>
            </div>
            @endif
            <table class="table cursor-pointer table-bordered table-hover" style="width:100%;">
                <thead>
                    <tr>
                        <th width="10px">Sequence</th>
                        <th>Media</th>
                        <th>Type</th>
                        <th width="150px">Action</th>
                    </tr>
                </thead>
                <tbody class="sortable">
                    @forelse ($product->images_by_sequence as $key => $image)
                        <tr class="image_row">
                            <td>
                                <input type="hidden" name="image_id[]"
                                    value="{{ $image->id }}">
                                {{ $image->sequence }}
                            </td>
                            <td width="50%">
                                @if ($image->source)
                                    @if( ($image->type == App\Models\ProductImage::TYPE_IMAGE) )
                                        <img style="height:100px" class="border" src="{{ $image->image_url }}">
                                    @elseif( $event_image['type'] == App\Models\ProductImage::TYPE_VIDEO )
                                        <video style="height:100px" controls>
                                            <source src="{{ $image->image_url }}" type="video/mp4">
                                            Your browser does not support the video tag.
                                        </video>
                                    @endif
                                @endif
                            </td>
                            <td>
                                {{ucfirst($image->type)}}
                            </td>
                            <td>
                                <button type="button" class="btn btn-sm btn-danger product_image_remove_btn"
                                    data-image_id="{{ $image->id }}">
                                    <i class="icon-trash"></i>
                                </button>
                            </td>

                        </tr>
                    @empty
                        <tr>
                            <td class="text-center" colspan="4">No record found</td>
                        </tr>
                    @endforelse

                </tbody>
            </table>
        </form>

    </x-panel-card>
    @endif

</div>

@endsection

@push('scripts')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Dropify/0.2.2/css/dropify.min.css" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/Dropify/0.2.2/js/dropify.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@9"></script>
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<script>

$(document).ready(function() {

    /**
     * Classes
     */
    const customFieldBoxClass = '.custom_field_box';
    const customFieldClass = '.custom_field';
    const addCustomFieldBtnClass = '.add_custom_field_btn'
    const deleteCustomFieldBtnClass = '.delete_custom_field_btn';
    const sortableTableClass = '.sortable';
    const productImageRemoveBtnClass = '.product_image_remove_btn';

    /**
     * Ids
     */
    const productImageId = '#productImage';

    /**
     * Variables
    */

    $(productImageId).dropify();

    function getCustomFieldHtml(number){
        return `<div class="form-group px-1 col-12 mb-2 custom_field">
                    <label for="message" class="w-100 text-left">Attribute ${number} </label>
                    <div class="row no-gutters">
                        <div class="col mb-3">
                            <label for="message" class="w-100 text-left">Key <span class="text-danger">*</span> </label>
                            <input type="text" class="form-control" name="custom_field[key][]" required>
                        </div>
                        <div class="col mb-3 pl-2">
                            <label for="message" class="w-100 text-left">Label <span class="text-danger">*</span> </label>
                            <input type="text" class="form-control" name="custom_field[label][]" required>
                        </div>
                        <div class="col mb-3 pl-2">
                            <label for="message" class="w-100 text-left">Type <span class="text-danger">*</span> </label>
                            <select class="form-control" name="custom_field[type][]" required>
                                <option value="">--select--</option>
                                <option value="text">Text</option>
                            </select>
                        </div>
                        <div class="col-1">
                            <label for="message" class="w-100 text-left">. </label>
                            <button class="btn btn-danger delete_custom_field_btn btn-sm ml-1">
                                <i class="icon icon-times s-18 pr-0"></i>
                            </button>
                        </div>
                    </div>
                </div>`;
    }

    /**
     * Event Handlers
    */

    $(addCustomFieldBtnClass).click(function(){
        let count = $(customFieldClass).length;
        let template = getCustomFieldHtml((count+1));
        $(customFieldBoxClass).append(template);
    });

    $(document).on('click', deleteCustomFieldBtnClass, function(){
        $(this).parent().parent().parent().remove();
    });

    $(sortableTableClass).sortable({
        revert: true
    });

    $(document).on('click', productImageRemoveBtnClass, function() {
        var image_id = $(this).data('image_id');
        var that = $(this)
        swal({
            title: "Are you sure?",
            text: "Once deleted, you will not be able to recover this Image!",
            icon: "warning",
            buttons: true,
            dangerMode: true,
        })
        .then((willDelete) => {
            if (willDelete) {
                $('#loader').show()
                // alert(image_id);
                $.ajax({
                    url: "{{route('product.image.delete')}}",
                    type: 'POST',
                    data: {
                        "image_id": image_id,
                        "type": 'product',
                        '_token': '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        if (response.status) {
                            $(that).parent().parent().remove();
                            $("#loader").fadeOut();
                            if( $('.image_row').length == 0 ){
                                $('.sortable').append(`<tr> <td class="text-center" colspan="4">No record found</td> </tr>`);
                            }

                        } else {
                            $("#loader").fadeOut();
                            swal({
                                title: response.message,
                                icon: "error"
                            })
                        }
                    },
                });
            };
        });
    });


});

</script>

@endpush