@props(['errors'])

@if ($errors->any())
    @foreach ($errors->all() as $error)
        <div role="alert" class="alert alert-danger">
            <strong> Ops! </strong> {{$error}}
        </div>
    @endforeach
@endif
