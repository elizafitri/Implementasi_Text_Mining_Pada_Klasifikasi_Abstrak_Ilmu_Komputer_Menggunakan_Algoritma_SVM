@extends('layouts.app2')

@section('content')
<div class="row wrapper border-bottom white-bg page-heading">
        <div class="col-lg-8">
            <h2>Daftar Jurnal Komputasi Jurusan Ilmu Komputer</h2>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="index.html">Beranda</a>
                    </li>
                    <li class="breadcrumb-item active">
                    <a href="{{route('jurnal')}}"><strong>Manajemen Jurnal</strong></a>
                    </li>      
                </ol>
        </div>
        <div class="col-lg-4">
            <div class="ibox-content center">
                <div class="title-action">
                <a href="{{route('addForm')}}"><button class="btn btn-primary float-right" type="submit"><i class="fa fa-pencil-square-o"></i> Tambah Data</button></a>
                </div>  
            </div>   
        </div>
    </div>
    
    <div class="wrapper wrapper-content animated fadeInRight">
        <div class="row">
            <div class="col-lg-12">
                <div class="ibox ">
                    <div class="ibox-title">
                        <h5>Jurnal</h5>
                    </div>
                    <div class="ibox-content">
                            <input type="text" class="form-control form-control-sm m-b-xs" id="filter"
                                   placeholder="Search in table">
                            <table class="footable table table-stripped toggle-arrow-tiny" data-filter=#filter>
                                <thead>
                                    <tr>
                                        <th data-toggle="true">Judul Jurnal</th>
                                        <th>Nama Penulis</th>
                                        <th>Topik Keilmuan</th>
                                            <th data-hide="all">Abstrak</th>
                                            <th data-hide="all">Kata Kunci</th>
                                            <th data-hide="all">Aksi</th>
                                        </tr>
                                        </thead>
                                        <tbody>

                                        @foreach ($jurnal as $jurkom)

                                        <tr>
                                            <td>{{ $jurkom->judul_jurnal }}</td>
                                            <td>{{ $jurkom->nama_penulis }}</td>
                                            <td>{{ $jurkom->keilmuan->kelas_keilmuan }}</td>
                                            <td>{{ $jurkom->abstrak }}</td>
                                            <td><span class="label label-primary">{{ $jurkom->kata_kunci }}</span></td>
                                            <td>
                                                <a href="{{ route('edit', ['id' => $jurkom->id]) }}"><button class="btn btn-primary" href="#"><i class="fa fa-pencil"></i></button></a>
                                                <a href="{{ route('jurnal.delete', ['id' => $jurkom->id]) }}"><button class="btn btn-danger" href="#"><i class="fa fa-trash"></i></button></a>
                                                <a href="{{ route('preproses.preproses', ['id' => $jurkom->id]) }}"><button class="btn btn-warning" href="#"><i class="fa fa-eye"></i></button></a>
                                                <a href="{{ route('edit.pre', ['id' => $jurkom->id]) }}"><button class="btn btn-success" href="#"><i class="fa fa-pencil"></i> Preproses </button></a>
                                                <a href="{{ route('bobot', ['id' => $jurkom->id]) }}"><button class="btn btn-success" href="#"><i class="fa fa-pencil"></i> Lihat Vector </button></a>
                                            </td>
                                        </tr>

                                        @endforeach

                                        </tbody>
                                        <tfoot>
                                        <tr>
                                            <td colspan="5">
                                                <ul class="pagination float-right"></ul>
                                            </td>
                                        </tr>
                                        </tfoot>
                            </table>
                        
                    </div>
                </div>
            </div>
        </div>
    </div>        
@endsection
