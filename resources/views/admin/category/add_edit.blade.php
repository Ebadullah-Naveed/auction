@extends('layouts.admin.app', [ 'page' => 'category_management', 'title'=> $title ])

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

            {{-- <input type="hidden" name="{{$user?'updated_by_id':'created_by_id'}}" value="{{auth()->user()->id}}"> --}}

            <div class="form-row">
                <input type="hidden" name="user_id" value="{{$user?$user->id:''}}" >
                <div class="form-group col-6 mb-2">
                    <label for="name" class="w-100 text-left">Name <span class="text-danger">*</span> </label>
                    <input type="text" class="form-control" name="name" placeholder="Name" value="{{$user?$user->name:old('name')}}" required>
                    @error('name')
                        <div class="invalid-feedback"> {{$message}} </div>
                    @enderror
                </div>

                <div class="form-group col-2 my-2 mb-2 pl-4">
                    <label for="status" class="xcol-form-label s-12 mr-4">Status</label>
                    <div class="d-flex justify-content-left align-items-center">
                        <div class="material-switch">
                            <input id="statusOption" name="status" type="checkbox"  {{($user && ($user->status==App\Models\Category::STATUS_ACTIVE))?'checked':''}} >
                            <label for="statusOption" class="bg-success"></label>
                        </div>
                    </div>
                </div>
                <div class="form-group col-2 my-2 mb-2">
                    <label for="featured" class="xcol-form-label s-12 mr-4">Featured</label>
                    <div class="d-flex justify-content-left align-items-center">
                        <div class="material-switch">
                            <input id="featuredOption" name="featured" type="checkbox"  {{($user && ($user->featured==App\Models\Category::FEATURED_YES))?'checked':''}} >
                            <label for="featuredOption" class="bg-success"></label>
                        </div>
                    </div>
                </div>

                <div class="form-group col-12 mb-3">
                    <label for="image" class="w-100 text-left">Image</label>
                    <input type="file" class="form-control dropify" name="image" id="categoryImage" data-allowed-file-extensions="jpg png jpeg" data-max-file-size="1M" @if($user && $user->image) data-default-file="{{$user->m_image}}" @endif>
                    @error('image')
                        <div class="invalid-feedback"> {{$message}} </div>
                    @enderror
                </div>

                <div class="col-12">
                    <hr>
                </div>
                <div class="col-12 custom_field_box">
                    @if( $user )
                    @foreach( $user->m_attribute_json as $key=>$item )
                    <div class="form-group px-1 col-12 mb-2 custom_field">
                        <label for="message" class="w-100 text-left">Attribute {{$key+1}} </label>
                        <div class="row no-gutters">
                            <div class="col mb-3">
                                <label for="message" class="w-100 text-left">Key <span class="text-danger">*</span> </label>
                                <input type="text" class="form-control" name="custom_field[key][]" value="{{$item->key}}" required>
                            </div>
                            <div class="col mb-3 pl-2">
                                <label for="message" class="w-100 text-left">Label <span class="text-danger">*</span> </label>
                                <input type="text" class="form-control" name="custom_field[label][]" value="{{$item->label}}" required>
                            </div>
                            <div class="col mb-3 pl-2">
                                <label for="message" class="w-100 text-left">Required <span class="text-danger">*</span> </label>
                                <select class="form-control" name="custom_field[required][]" required>
                                    <option value="1" {{$item->required==1?'selected':''}} >Yes</option>
                                    <option value="0" {{$item->required==0?'selected':''}}>No</option>
                                </select>
                            </div>
                            <div class="col mb-3 pl-2">
                                <label for="message" class="w-100 text-left">Type <span class="text-danger">*</span> </label>
                                <select class="form-control" name="custom_field[type][]" required>
                                    <option value="">--select--</option>
                                    <option value="text" selected>Text</option>
                                </select>
                            </div>
                            <div class="col-1">
                                <label for="message" class="w-100 text-left">. </label>
                                <button class="btn btn-danger delete_custom_field_btn btn-sm ml-1">
                                    <i class="icon icon-times s-18 pr-0"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    @endforeach
                    @endif
                </div>

            </div>

            <div>
                <button class="btn btn-primary" type="submit">Submit</button>
                <button type="button" class="btn btn-success add_custom_field_btn"> Add Attribute </button>
            </div>

        </form>

    </x-panel-card>

</div>

@endsection

@push('scripts')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Dropify/0.2.2/css/dropify.min.css" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/Dropify/0.2.2/js/dropify.min.js"></script>
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
    const categoryImageId = '#categoryImage';

    /**
     * Variables
    */

    $(categoryImageId).dropify();

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
                            <label for="message" class="w-100 text-left">Required <span class="text-danger">*</span> </label>
                            <select class="form-control" name="custom_field[required][]" required>
                                <option value="1">Yes</option>
                                <option value="0">No</option>
                            </select>
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