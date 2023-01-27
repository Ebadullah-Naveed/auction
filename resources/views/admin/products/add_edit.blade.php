@extends('layouts.admin.app', [ 'page' => 'product_management', 'title'=> $title ])

@push('css')

@endpush

@section('content')

<div class="row row-eq-height my-3">

    <x-panel-card class="" >
        <x-slot name="title">
            {{$title}}
        </x-slot>
        
        <form action="{{$action_url}}" method="POST" class="needs-validation @if($errors->any()) was-validated @endif" novalidate>
            @csrf

            {{-- <input type="hidden" name="{{$product?'updated_by_id':'created_by_id'}}" value="{{auth()->user()->id}}"> --}}
            @if($errors->any())
    {{ implode('', $errors->all('<div>:message</div>')) }}
@endif
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
                    <input type="text" class="form-control date-time-picker" name="end_datetime" value="2022/12/01 10:30" xvalue="{{$product?$product->end_datetime:old('end_datetime')}}" required>
                    @error('end_datetime')
                        <div class="invalid-feedback"> {{$message}} </div>
                    @enderror
                </div>

                <div class="col-12">
                    <hr>
                    <h5 class="mb-2"> Attributes </h5>
                </div>

                @foreach( $category->m_attribute_json as $attr )
                    <div class="form-group col-6 mb-2">
                        <input type="hidden" name="product_attributes_label[{{$attr->key}}]" value="{{$attr->label}}">
                        <label for="end_datetime" class="w-100 text-left">{{$attr->label}}  {!!($attr->required==1)?'<span class="text-danger">*</span>':''!!} </label>
                        <input type="text" class="form-control" 
                            name="product_attributes[{{$attr->key}}]"
                            xvalue="{{($product&&($product))?$product->end_datetime:old('end_datetime')}}" 
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

                <div class="form-group col-12 my-2 mb-2 pl-4">
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

</div>

@endsection

@push('scripts')

<script>

$(document).ready(function() {

    /**
     * Classes
     */
    const customFieldBoxClass = '.custom_field_box';
    const customFieldClass = '.custom_field';
    const addCustomFieldBtnClass = '.add_custom_field_btn'
    const deleteCustomFieldBtnClass = '.delete_custom_field_btn';

    /**
     * Ids
     */


    /**
     * Variables
    */

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

});

</script>

@endpush