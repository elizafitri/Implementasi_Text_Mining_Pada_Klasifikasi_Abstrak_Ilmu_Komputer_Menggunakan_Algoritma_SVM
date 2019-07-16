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
                    <a href="{{route('jurnal')}}"><strong>Token</strong></a>
                    </li>      
                </ol>
        </div>
    </div>
    
    <div class="wrapper wrapper-content animated fadeInRight">
        <div class="row">
            <div class="col-lg-12">
                <div class="ibox ">
                    <div class="ibox-title">
                        <h5>Token</h5>
                    </div>
                    <div class="ibox-content">
                        <div class="table-responsive"">  
                            <table id="token"class="table table-striped table-bordered table-hover" >
                                <thead>
                                    <tr>
                                        <th>id</th>
                                        <th>Token</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                @foreach ($token as $data)
                                    <tr>
                                        <td>{{ $data->id}}</td>
                                        <td>{{ $data->token}}</td>
                                        <td>
                                        <button class="btn btn-danger hapus" href="#" id="id" data-toggle='modal' data-id="{{ $data->id }}"><i class="fa fa-trash"></i></button>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                                <!-- <tfoot>
                                    <tr>
                                        <th>id</th>
                                        <th>Token</th>
                                        <th>Aksi</th>
                                    </tr>
                                </tfoot> -->
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>   
       
@endsection

