<!DOCTYPE html>

<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<html>
<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Admin Jurnal Komputasi</title>

    <link href="{{ asset ('assets/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset ('assets/font-awesome/css/font-awesome.css') }}" rel="stylesheet">

    <link href="{{ asset ('assets/css/animate.css') }}" rel="stylesheet">
    <link href="{{ asset ('assets/css/style.css') }}" rel="stylesheet">
</head>

<body class="gray-bg">

    <div class="middle-box text-center loginscreen animated fadeInDown">
        <div class="ibox-content">
            <div>
                <h1 class="logo-name">EF</h1>

            </div>
            <p>Login in. To see it in action.</p>
            <form method="POST" action="{{ route('login') }}">
                @csrf
                <div class="form-group">
                    <input name="email" type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" placeholder="Username" required="">
                    @if ($errors->has('email'))
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $errors->first('email') }}</strong>
                    </span>
                @endif
                </div>
                <div class="form-group">
                    <input name="password" type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" placeholder="Password" required="">
                    @if ($errors->has('password'))
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $errors->first('password') }}</strong>
                    </span>
                @endif
                </div>
                <button type="submit" class="btn btn-primary block full-width m-b">Login</button>

            </form>
            <p class="m-t"> <small>Inspinia we app framework base on Bootstrap 3 &copy; 2014</small> </p>
        </div>
    </div>


    <!-- Mainly scripts -->
    <script src="{{ asset ('assets/js/jquery-3.1.1.min.js') }}"></script>
    <script src="{{ asset ('assets/js/popper.min.js') }}"></script>
    <script src="{{ asset ('assets/js/bootstrap.js') }}"></script>

    <script src="{{ asset ('assets/js/plugins/metisMenu/jquery.metisMenu.js') }}"></script>
    <script src="{{ asset ('assets/js/plugins/slimscroll/jquery.slimscroll.min.js') }}"></script>

</body>


</html>
