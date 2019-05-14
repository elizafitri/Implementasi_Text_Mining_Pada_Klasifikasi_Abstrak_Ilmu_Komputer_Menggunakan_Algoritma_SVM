@extends('layouts.app2')

@section('content')
<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox ">
                <div class="ibox-title">
                    <h5>TAHAP PREPROSES ABSTRAK</h5>
                </div>

                <div class="ibox-title">
                    <h2>Input Token</h2>
                    <div class="ibox-content">
                        <form action="{{ route('hasil.pre', ['id' => $jurnal->id]) }}) }}" method="post">
                        {{ csrf_field() }}
                             <!-- memanggil token yang ada di fungsi CreateToken di PreprosesController -->
                            <input type="text" value="{{$jurnal->id}}" name="id">
                            <textarea  rows="10"  type="text" placeholder="Abstrak" class="form-control"  name="preproses" required>{{ $abstrak }}</textarea>
                            <div class="modal-footer">
                                <a href="{{ route('jurnal') }}"><button type="button" class="btn btn-danger">Batal</button></a>
                                <button type="submit" class="btn btn-primary">Simpan Token</button>
                            </div>
                        </form>
                    </div>
                </div>
                            
            </div>
        </div>
    </div>
</div>


@endsection