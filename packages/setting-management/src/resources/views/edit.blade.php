@extends('layouts.admin.app', [ 'page' => 'setting', 'title'=> $title ])

@push('css')
<style>
    .form-control {
        font-size: 12px;
    }
</style>
@endpush

@section('content')

<div class="row row-eq-height my-3">

    <x-panel-card class="col-md-12 mx-auto" >
        <x-slot name="title">
            {{$title}}
        </x-slot>
        
        <form action="{{$action_url}}" method="POST" class="needs-validation" novalidate>
            @csrf
            <table class="table table-striped datatable-nowrap">
                <tr>
                    <th width="10%">Title</th>
                    <th width="50%">Value</th>
                    <th width="40%">Description</th>
                </tr>
                @foreach( $settings as $key=>$item )
                    <tr>
                        <td>
                            {{ $item->title }}
                        </td>
                        <td>
                            
                            @if( $item->json_params && $item->json_params['type'] == "dropdown" )
                            <select class="form-control" name="{{$item->key}}">
                                @foreach ($item->json_params['data'] as $option)
                                <option value="{{$option['value']}}" @if( $item->value == $option['value'] ) selected @endif
                                    > {{$option['label']}} </option>
                                @endforeach
                            </select>
                            @elseif( $item->json_params && $item->json_params['type'] == "multiselect" )
                            <select class="form-controlx custom-select select2" name="{{$item->key}}[]" multiple="multiple">
                                @php 
                                    $data = explode(',',$item->value);
                                @endphp
                                @foreach ($item->json_params['data'] as $option)
                                <option value="{{$option['value']}}" @if( in_array($option['value'],$data) ) selected @endif
                                    > {{$option['label']}} </option>
                                @endforeach
                            </select>
                            @else
                            <input type="text" class="form-control" name="{{$item->key}}" value="{{$item->value}}">
                            @endif
                        
                        </td>
                        <td>
                            {{ $item->description }}
                        </td>
                    </tr>
                @endforeach
            </table>
            @if( App\Models\Setting::canEdit() )
            <div>
                <button class="btn btn-primary" type="submit">Update</button>
            </div>
            @endif
        </form>

    </x-panel-card>

</div>


@endsection

@push('scripts')

@endpush