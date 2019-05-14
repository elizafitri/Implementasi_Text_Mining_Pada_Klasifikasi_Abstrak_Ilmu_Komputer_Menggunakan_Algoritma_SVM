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

    <link href="{{ asset ('assets/css/plugins/select2/select2.min.css') }}" rel="stylesheet">

    <link href="{{ asset ('assets/css/plugins/footable/footable.core.css') }}" rel="stylesheet">  
    
    <link href="{{ asset ('assets/css/plugins/bootstrap-tagsinput/bootstrap-tagsinput.css') }}" rel="stylesheet">
    <link href="{{ asset ('assets/css/plugins/dataTables/datatables.min.css') }}" rel="stylesheet">

    <link href="{{ asset ('assets/css/plugins/select2/select2.min.css') }}" rel="stylesheet">
    
    <link rel="stylesheet" href="{{ asset ('assets/plugins/sweetalert2/dist/sweetalert2.all.min.css') }}">

</head>

<body>

<div id="wrapper">
<!-- main menu -->
@include('partial.main_menu')
<!-- end main menu -->

        <div id="page-wrapper" class="gray-bg">
            <div class="row border-bottom">
             <!-- header -->
             @include('partial.header')
            <!-- end header -->
            </div>

            <!-- content -->
            @yield('content')
            <!-- end content -->


            <!-- footer -->
            @include('partial.footer')
            <!-- end footer -->

        </div>
</div>


    <!-- Mainly scripts -->
    <script src="{{ asset ('assets/js/jquery-3.1.1.min.js') }}"></script>
    <script src="{{ asset ('assets/js/popper.min.js') }}"></script>
    <script src="{{ asset ('assets/js/bootstrap.js') }}"></script>

    <script src="{{ asset ('assets/js/plugins/metisMenu/jquery.metisMenu.js') }}"></script>
    <script src="{{ asset ('assets/js/plugins/slimscroll/jquery.slimscroll.min.js') }}"></script>

    <!-- Custom and plugin javascript -->
    <script src="{{ asset ('assets/js/inspinia.js') }}"></script>
    <script src="{{ asset ('assets/js/plugins/pace/pace.min.js') }}"></script>

    <script src="{{ asset ('assets/js/plugins/footable/footable.all.min.js') }}"></script> 

    <script src="{{ asset ('assets/js/plugins/select2/select2.full.min.js') }}"></script>


    <script src="{{ asset ('assets/js/plugins/dataTables/datatables.min.js') }}"></script>
    <script src="{{ asset ('assets/js/plugins/dataTables/dataTables.bootstrap4.min.js') }}"></script>

    <script src="{{ asset ('assets/js/plugins/bootstrap-tagsinput/bootstrap-tagsinput.js') }}"></script>
   <!-- <script src="{{ asset ('assets/js/plugins/sweetalert/sweetalert.min.js') }}"></script> -->
    <script src="{{ asset ('assets/plugins/sweetalert2/dist/sweetalert2.all.min.js') }}"></script>


    <script>
        $(document).ready(function() {
    
            $('.footable').footable();
            $('.footable2').footable();
        });
    
    </script>
   <script>
    //    $(document).ready(function() {
    //         $(window).keydown(function(event){
    //             if(event.keyCode == 13) {
    //             event.preventDefault();
    //             return false;
    //             }
    //         });
    //     });
        $(document).ready(function(){

            $('#tagsinput').tagsinput({
                confirmKeys: [13, 188],
                tagClass: 'label label-primary'
            });

            $('#tagsinput input').on('keypress', function(e){
                if (e.keyCode == 13){
                e.keyCode = 188;
                e.preventDefault();
            };
  });
        });


    </script>
</body>


</html>
