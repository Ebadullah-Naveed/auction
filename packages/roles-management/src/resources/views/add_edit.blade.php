@extends('layouts.admin.app', [ 'page' => 'role', 'title'=> $title ])

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
            <div class="form-row">
                
                <div class="form-group col-md-12 mb-3">
                    <label for="name" class="w-100 text-left">Name <span class="text-danger">*</span> </label>
                    <input type="text" class="form-control" name="name" placeholder="Name" value="{{$role?$role->name:old('name')}}" required>
                    @error('name')
                        <div class="invalid-feedback"> {{$message}} </div>
                    @enderror
                </div>

                <div class="col-md-12">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Module</th>
                                <th>List</th>
                                <th>View</th>
                                <th>Add</th>
                                <th>Edit</th>
                                <th>Delete</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $groups = App\Models\Permission::getGroupData();
                                $selected_roles = [];
                            @endphp
                            @if($role)
                                @php
                                    $selected_roles =  $role->permissions->pluck('id')->toArray();
                                @endphp
                            @endif
                            @foreach( $groups as $group )

                            <tr>
                                <td>{{$group['name']}}</td>
                                @foreach($group['permissions'] as $permission)
                                    <td>
                                        @if( $permission != '' )
                                            <input type="checkbox" id="permission{{$arranged_permission[$permission]['id']}}" 
                                            name="permissions[]" value="{{$arranged_permission[$permission]['id']}}"
                                            @if(in_array( $arranged_permission[$permission]['id'] , $selected_roles )) checked @endif
                                            >
                                        @else
                                            <input type="checkbox" class="cursor-not-allowed" id="permission{{$permission}}" disabled title="Functionality does not exist.">
                                        @endif
                                    </td>
                                @endforeach
                                
                            </tr>

                            @endforeach

                        </tbody>
                    </table>
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

@endpush