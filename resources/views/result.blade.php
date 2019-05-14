<!DOCTYPE html>
<html>
<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>JURNAL KOMPUTASI | Hasil Pencarian</title>

    <link href="{{ asset('assets/css/bootstrap.min.css')}}" rel="stylesheet">
    <link href="{{ asset('assets/font-awesome/css/font-awesome.css')}}" rel="stylesheet">
    <link href="{{ asset('assets/css/animate.css')}}" rel="stylesheet">
    <link href="{{ asset('assets/css/style.css')}}" rel="stylesheet">

</head>
<body>
<div id="wrapper">
    <div id="content" class="gray-bg">
        
        <div class="row border-bottom">
        <nav class="navbar navbar-static-top white-bg" role="navigation" style="margin-bottom: 0">
        <div class="navbar-header">
            <form role="search" class="navbar-form-custom" action="http://webapplayers.com/inspinia_admin-v2.9.1/search_results.html">
                <div class="form-group"></div>
            </form>
        </div>
            <ul class="nav navbar-top-links navbar-left">
                <li>
                    <span class="m-r-sm text-muted welcome-message float-left">Sistem Temu Kembali Informasi Jurnal Komputasi | UNILA</span>
                </li>
            </ul>

        </nav>
        </div>
        
        <div class="wrapper wrapper-content animated fadeInRight">
            <div class="row">
                <div class="col-lg-12">
                    <div class="ibox ">
                        <div class="ibox-content">
                            <div class="col-lg-9">
                            <h2>
                                {{$hasil}} hasil untuk kata kunci: <span value="{{ old('cari') }}" class="text-navy">{{ $cari }}</span>
                            </h2>
                            <small>{{ (microtime(true)) - LARAVEL_START}} detik</small>
                            <div class="search-form">
                            <form class="m-t" role="form" action="{{route('cari')}}" method="GET">
                                    <div class="input-group">
                                        <input type="text" placeholder="Admin Theme" name="cari" value="{{$cari}}" class="form-control form-control-lg">
                                        <div class="input-group-btn">
                                            <button class="btn btn-lg btn-primary" name="CARI" type="submit"> Cari </button>
                                        </div>
                                    </div>
                            </form>
                            </div>
                            
                            @foreach ($klasifikasis as $jurkom)
                            
                            <div class="hr-line-dashed"></div>
                            <div class="search-result">
                                <h3><a href="#">{{$jurkom->judul_jurnal}}</a></h3>
                                <p>{{$jurkom->abstrak}}</p>
                            </div>
                            <div class="hr-line-dashed"></div>
                            
                            @endforeach

                            @if ($hasil == 0 )
                            <div class="middle-box text-center animated fadeInDown">
                                <h1><i class="fa fa-search"></i></h1>
                                    <h3> <b>{{$cari }} </b> tidak ditemukan</h3>
                                    <div class="error-desc">
                                        masukan kata kunci yang lain
                                    </div>
                            </div>
                            @endif

                        </div>
                    </div>
                </div>
            </div>
            <div class="footer">
            <div class="float-right">
                <strong>Copyright</strong> Eliza Fitri &copy; 2019
            </div>
        </div>
        </div>
    </div>
</div>


    <!-- Mainly scripts -->
    <script src="{{ asset('assets/js/jquery-3.1.1.min.js')}}"></script>
    <script src="{{ asset('assets/js/popper.min.js')}}"></script>
    <script src="{{ asset('assets/js/bootstrap.js')}}"></script>
    <script src="{{ asset('assets/js/plugins/metisMenu/jquery.metisMenu.js')}}"></script>
    <script src="{{ asset('assets/js/plugins/slimscroll/jquery.slimscroll.min.js')}}"></script>

    <!-- Custom and plugin javascript -->
    <script src="{{ asset('assets/js/inspinia.js')}}"></script>
    <script src="{{ asset('assets/js/plugins/pace/pace.min.js')}}"></script>

</body>
<!-- Mirrored from webapplayers.com/inspinia_admin-v2.9.1/search_results.html by HTTrack Website Copier/3.x [XR&CO'2014], Fri, 18 Jan 2019 10:35:19 GMT -->
</html>
