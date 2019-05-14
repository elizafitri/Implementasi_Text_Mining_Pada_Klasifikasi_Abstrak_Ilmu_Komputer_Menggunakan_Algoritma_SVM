@extends('layouts.app2')

@section('content')
<div class="wrapper wrapper-content">
    <div class="row">
        <div class="col-lg-3">
            <div class="ibox ">
                <div class="ibox-title">
                    <!-- <span class="label label-success float-right"></span> -->
                        <h5>Jumlah Jurnal</h5>
                </div>
                <div class="ibox-content">
                <h1 class="no-margins">{{ $jumlah }}</h1>
                        <small>Jurnal Komputasi</small>
                </div>
            </div>
        </div>
        <div class="col-lg-3">
            <div class="ibox ">
                <div class="ibox-title">
                    <!-- <span class="label label-success float-right">Monthly</span> -->
                        <h5>Kategori Topik</h5>
                </div>
                <div class="ibox-content">
                    <h1 class="no-margins">{{ $kategori }}</h1>
                        <small>Kelas Keilmuan</small>
                    </div>
                </div>
            </div>
            <!-- <div class="col-lg-3">
                <div class="ibox ">
                    <div class="ibox-title">
                        <span class="label label-success float-right">Monthly</span>
                            <h5>Income</h5>
                    </div>
                    <div class="ibox-content">
                        <h1 class="no-margins">40 886,200</h1>
                            <div class="stat-percent font-bold text-success">98% <i class="fa fa-bolt"></i></div>
                            <small>Total income</small>
                    </div>
                </div>
            </div> -->
    </div>
</div>
@endsection
