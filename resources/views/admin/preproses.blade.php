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
                    <h5>Abstrak asli</h5>
                    <p>{{ $jurnal->abstrak }}</p>
                </div>

                <div class="ibox-title">
                    <h5>Hasil Case Folding</h5>
                    <!-- nah ini fungsi yang dipanggil di view pada PreprosesController.blade.php -->
                    <p>{{$low}}</p>
                </div>

                <div class="ibox-title">
                    <h5>Hasil Stopword Removal</h5>
                    <!-- nah ini fungsi yang dipanggil di view pada PreprosesController.blade.php -->
                    <p>{{$hapus_stopword}}</p>
                </div>
                
                <div class="ibox-title">
                    <h5>Hasil Tokenisasi</h5>
                    <!-- nah ini fungsi yang dipanggil di view pada PreprosesController.blade.php -->
                    <pre>{{$token}}</pre> 
                    <!-- ini juga sama kayak yang diatas -->
                    <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Simpan Preproses</button>
                    </div>
                </div> 

                <div class="ibox-title">
                    <h5>Hasil Stemming</h5>
                    <!-- nah ini fungsi yang dipanggil di view pada PreprosesController.blade.php -->
                    <p>{{$stem}}</p> 
                    <!-- <textarea  rows="10"  type="text" placeholder="Abstrak" class="form-control"  name="abstrak" required>{{ $stem }}</textarea> -->
                </div>

                <div class="ibox-title">
                    <h2>Input Token</h2>
                    <p>Jumlah Kata = {{$hasil_token}}</p>
                    <div class="ibox-content">
                        <form action="{{ route('create.token') }}" method="post">
                        {{ csrf_field() }}
                            @foreach($input as $he)
                             <!-- memanggil token yang ada di fungsi CreateToken di PreprosesController -->
                                <input value="{{ $he }}" type="text" name="token[]">
                            @endforeach
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