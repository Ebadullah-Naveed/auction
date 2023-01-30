@php
    $settings = App\Models\Setting::getValues(['version']);
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="{{asset('favicon.ico')}}" type="image/x-icon">
    {{-- <link rel="icon" href="{{asset('admin/assets/img/basic/favicon.png')}}" type="image/x-icon"> --}}
    <title>{{ config('app.name', 'Admin Panel') }}</title>
    <!-- CSS -->
    <link rel="stylesheet" href="{{asset('admin/assets/css/app.min.css')}}">
    <link rel="stylesheet" href="{{asset('admin/assets/css/style.css')}}?v={{$settings['version']}}">

    @stack('css')
</head>
<body class="light">
<!-- Pre loader -->
<div id="loader" class="loader">
    <div class="plane-container">
        <div class="preloader-wrapper big active">
            <div class="spinner-layer spinner-blue">
                <div class="circle-clipper left">
                    <div class="circle"></div>
                </div><div class="gap-patch">
                <div class="circle"></div>
            </div><div class="circle-clipper right">
                <div class="circle"></div>
            </div>
            </div>
            <div class="spinner-layer spinner-purple">
                <div class="circle-clipper left">
                    <div class="circle"></div>
                </div><div class="gap-patch">
                <div class="circle"></div>
            </div><div class="circle-clipper right">
                <div class="circle"></div>
            </div>
            </div>
            <div class="spinner-layer spinner-purple-blue">
                <div class="circle-clipper left">
                    <div class="circle"></div>
                </div><div class="gap-patch">
                <div class="circle"></div>
            </div><div class="circle-clipper right">
                <div class="circle"></div>
            </div>
            </div>
        </div>
    </div>
</div>
<div id="app">
    <aside class="main-sidebar fixed offcanvas shadow bg-primary text-white no-b ">
        @include('layouts.admin.sidenav')
    </aside>
    <!--Sidebar End-->
    <div class="has-sidebar-left">

        <div class="sticky">
            <div class="navbar navbar-expand d-flex justify-content-between bd-navbar white shadow">
                <div class="relative">
                    <div class="d-flex">
                        <div>
                            <a href="#"  data-toggle="push-menu" class="paper-nav-toggle pp-nav-toggle">
                                <i></i>
                            </a>
                        </div>
                        <div class="d-none d-md-block">
                            <h1 class="nav-title text-white">Dashboard</h1>
                        </div>
                    </div>
                </div>
                <!--Top Menu Start -->
                <div class="navbar-custom-menu">
                    <ul class="nav navbar-nav">
                        <!-- User Account-->
                        <li class="dropdown custom-dropdown user user-menu ">
                            <a href="#" class="nav-link" data-toggle="dropdown" role="button" id="dropdownMenuLink" aria-haspopup="true" aria-expanded="false">
                                <img src="{{ auth()->user()->image }}" class="user-image" alt="User Image">
                                {{ auth()->user()->name }}
                                <i class="icon-more_vert "></i>
                            </a>
                            <div class="dropdown-menu myaccount-dd" aria-labelledby="dropdownMenuLink" >
                                <a class="dropdown-item" disabled>{{ auth()->user()->name }} ({{ auth()->user()->role->name }})</a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="{{ route('admin.profile.password_reset') }}">Change Password</a>
                                <a class="dropdown-item" href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Logout</a>
                            </div>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                @csrf
                            </form>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="container-fluid animatedParent animateOnce my-3">
            <div class="animated fadeInUpShortx">
                @yield('content')
            </div>
        </div>
        
    </div>
    
    {{-- Include Modals in layout --}}
    @yield('modals')

</div>

{{-- SweetAlert --}}
<script src="{{asset('admin/assets/sweetalert2/sweetalert2.min.js')}}"></script>
<link rel="stylesheet" href="{{asset('admin/assets/sweetalert2/sweetalert2.min.css')}}">

<!--/#app -->
<script src="{{asset('admin/assets/js/app.js')}}"></script>
<script src="{{asset('admin/assets/js/main.js')}}?v={{$settings['version']}}"></script>

<script>

    //Show Toast
    @if(Session::has('message'))
        Toast.fire({ title: "{{Session::get('title')}}", text: "{{Session::get('message')}}", type: "{{Session::get('type')}}" });
    @endif

    const datatablesLoaderHtml = ``;

</script>
@stack('scripts')
</body>
</html>