@extends('layouts.app2')

@section('content')
<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox ">
                <div class="ibox-title">
                <form action="{{ route('create.token') }}" method="post">
                    {{ csrf_field() }}
                    <h5>TAHAP PEMBOBOTAN KATA / TF</h5>
                </div>

                <div class="ibox-title">
                <h2>Input Token</h2>
                    <div class="ibox-content">
                        
                            <button class="btn btn-success">Simpan</button><hr>
                                
                        </form>
                    </div>
                </div>
                            
            </div>
        </div>
    </div>
</div>

@endsection