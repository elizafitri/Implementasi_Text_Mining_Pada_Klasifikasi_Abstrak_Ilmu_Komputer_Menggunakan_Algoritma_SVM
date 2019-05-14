@extends('layouts.app2')

@section('content')
<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox ">
                <div class="ibox-title">
                    <h5>Edit Data Jurnal Komputasi</h5>
                </div>
                <div class="ibox-content">
                    <form action="{{ route('jurnal.edit', ['id' => $jurnal->id]) }}" method="post" enctype="multipart/form-data" onkeypress="return event.keyCode != 13;">
                        {{ csrf_field() }}

                        <div class="form-group row">
                            <label class="col-lg-2 col-form-label"><b>Bidang Keilmuan</b></label>
                            <div class="col-sm-10">
                            <select class="form-control" name="id_keilmuan">
                                @foreach($keilmuan as $edit )
                                    <option value="{{ $edit->id }}">{{ $edit->kelas_keilmuan}}</option>
                                @endforeach
                            </select>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-lg-2 col-form-label"><b>Judul Jurnal</b></label>
                            <div class="col-lg-10">
                            <input type="text" value="{{ $jurnal->judul_jurnal }}" placeholder="Judul Jurnal" class="form-control" name="judul_jurnal" required> 
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-lg-2 col-form-label"><b>Nama Penulis</b></label>
                                <div class="col-lg-10">
                                    <input type="text" value="{{ $jurnal->nama_penulis }}" placeholder="Nama Penulis" class="form-control"  name="nama_penulis" required> 
                                </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-lg-2 col-form-label"><b>Abstrak</b></label>
                                <div class="col-lg-10">
                                    <textarea  rows="10"  type="text" placeholder="Abstrak" class="form-control"  name="abstrak" required>{{ $jurnal->abstrak }}</textarea>
                                </div>
                        </div>
                        
                        <div class="form-group row">
                            <label class="col-lg-2 col-form-label"><b>Kata Kunci</b></label>
                                <div class="col-lg-10">
                                    <input id="tagsinput" class="tagsinput form-control" type="text" value="{{ $jurnal->kata_kunci }}" name="kata_kunci" required/>
                                    @if ($errors->has('kata_kunci'))
                                        <span class="text-danger">{{ $errors->first('body') }}</span>
                                    @endif
                                </div>
                        </div>
                                
                        <div class="modal-footer">
                                <a href="{{ route('jurnal') }}"><button type="button" class="btn btn-danger">Batal</button></a>
                                <button type="submit" class="btn btn-primary">Simpan</button>
                                
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>


@endsection