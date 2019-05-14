<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>JURNAL KOMPUTASI | Jurusan Ilmu Komputer</title>

    <link href="{{ asset('assets/css/bootstrap.min.css')}}" rel="stylesheet">
    <link href="{{ asset('assets/font-awesome/css/font-awesome.css')}}" rel="stylesheet">
    <link href="{{ asset('assets/css/plugins/iCheck/custom.css')}}" rel="stylesheet">
    <link href="{{ asset('assets/css/animate.css')}}" rel="stylesheet">
    <link href="{{ asset('assets/css/style.css')}}" rel="stylesheet">
    </head>
    
    <body class="gray-bg">
            
    <div class="middle-box text-center animated fadeInDown">
        <div>
            <div>
                <h2 class="logo-name text-center">JURNAL KOMPUTASI</h2>
            </div>
            <form class="m-t" role="form" action="{{route('cari')}}" method="GET">
                <div class="form-group text-center">
                    <div class="col-sm-15">
                        <div class="input-group">
                        <input type="text" class="form-control" name="cari" value="{{ old('cari') }}" placeholder="Masukkan kata kunci pencarian" required="">
                        <span class="input-group-appened">
                            <button type="submit" value='CARI' class="btn btn-primary"><i class="fa fa-search"></i> Cari</button>
                        </span>
                        </div>
                    </div>
                    
                </div>

                </form>
            <p class="m-t"> <small>System by Eliza Fitri &copy; 2019</small> </p>
        </div>
    </div>

    <!-- Mainly scripts -->
    <script src="{{asset('assets/js/jquery-3.1.1.min.js')}}"></script>
    <script src="{{asset('assets/js/popper.min.js')}}"></script>
    <script src="{{asset('assets/js/bootstrap.js')}}"></script>

                <!-- <div class="links">
                    <a href="https://laravel.com/docs">Documentation</a>
                    <a href="https://laracasts.com">Laracasts</a>
                    <a href="https://laravel-news.com">News</a>
                    <a href="https://nova.laravel.com">Nova</a>
                    <a href="https://forge.laravel.com">Forge</a>
                    <a href="https://github.com/laravel/laravel">GitHub</a>
                </div> -->
            </div>
        </div>
    </body>
</html>
