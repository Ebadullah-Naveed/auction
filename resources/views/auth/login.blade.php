<!DOCTYPE html>
<html lang="zxx">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="{{asset('admin/assets/img/basic/favicon.ico')}}" type="image/x-icon">
    <title>{{ config('app.name', 'Admin Panel').' - Login' }}</title>
    <!-- CSS -->
    {{-- <link rel="stylesheet" href="{{asset('admin/assets/css/app.css')}}"> --}}
    <link rel="stylesheet" href="{{asset('admin/assets/css/app.min.css')}}">
    <link rel="stylesheet" href="{{asset('admin/assets/css/style.css')}}">
</head>
<body class="light" onload="var loader = document.getElementById('loader');loader.style.display = 'none';">
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
<main>
    <div id="primary" class="p-t-b-100 height-full">
        <div class="container">
            <div class="row">
                <div class="col-lg-4 mx-md-auto paper-card z-1">
                    <div class="text-center">
                        <h3 class="mt-1">{{config('app.name', '')}} Sign In</h3>
                        <p class="p-t-b-20x"></p>
                    </div>

                    <!-- Validation Errors -->
                    <x-auth-validation-errors class="mb-4" :errors="$errors" />

                    <form method="POST" action="{{ route('login') }}" id="loginForm" onsubmit="showLoader()">
                        @csrf
                        <div class="form-group has-icon"><i class="icon-envelope-o"></i>
                            <input type="text" class="form-control form-control-lg"
                                name="email"
                                placeholder="Email Address"
                                required
                                autofocus
                                >
                        </div>
                        <div class="form-group has-icon"><i class="icon-user-secret"></i>
                            <input type="password" class="form-control form-control-lg"
                                name="password"
                                required autocomplete="current-password"
                                placeholder="Password"
                                required
                                >
                        </div>
                        <input type="submit" class="btn btn-primary btn-lg btn-block login_btn" value="Log In">
                        
                    </form>
                </div>
            </div>
        </div>

        <img src="{{asset('admin/assets/img/logo/logo_cyan.png')}}" class="bg-img-br z-0" alt="">

    </div>
    <!-- #primary -->
</main>

</div>
<!--/#app -->
{{-- <script defer src="{{asset('admin/assets/js/app.js')}}"></script> --}}
<script>
    function showLoader(){
        document.getElementById('loader').style = "display:block;";
    }
</script>

</body>
</html>
