@extends('layouts.app2')

@section('content')
<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox ">
                <div class="ibox-title">
                    <h5>TAHAP PEMBOBOTAN KATA</h5>
                </div>

                <div class="ibox-title">
                    <h2>Menghitung Nilai TF-IDF</h2>
                    <div class="ibox-content">
                        
                        <form action="{{ route('save.tfidf') }}" method="post">
                        {{ csrf_field() }}
                            <button class="btn btn-success">Simpan</button><hr>
                            @foreach ($idf as $f)
                                <?php $id_term = $f->id;?>
                                <?php $tf = $f->tf;?>
                                <?php $idf = $f->idf; ?> 
                                <?php $tfidf = $tf*($idf+1); ?>

                                <input type="text" value="{{ $id_term }}" name="id_term[]">
                                <input type="text" value="{{ $tf }}" name="tf[]">
                                <input type="text" value="{{ $idf }}" name="idf[]">
                                <input type="text" value="{{ $tfidf }}" name="tfidf[]"><br>
                            @endforeach
                        </form>
                    </div>
                </div>
                            
            </div>
        </div>
    </div>
</div>


@endsection