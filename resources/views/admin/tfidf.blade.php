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
                                <?php 
                                    $tf = $f->tf;
                                    $idf = $f->idf;
                                    $tfidf = ($tf*$idf);
                                ?>
                                
                                <input type="text" value="{{ $tf }}" name="id_term[]">
                                <input type="text" value="{{ round ($tfidf, 4) }}" name="tfidf[]"><br>
                            @endforeach
                        </form>
                    </div>
                </div>
                            
            </div>
        </div>
    </div>
</div>


@endsection