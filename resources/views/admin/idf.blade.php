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
                    <h2>Menghitung Nilai IDF</h2>
                    <div class="ibox-content">
                        
                        <form action="{{ route('save.idf') }}" method="post">
                        {{ csrf_field() }}
                            <button class="btn btn-success">Simpan</button><hr>
                            @foreach ($df as $d)
                                <?php $id_term = $d->id;?>
                                <?php $df = $d->df; ?> 
                                <?php $idf = log($doc/$df); ?>

                                <input type="text" value="{{ $id_term }}" name="id_term[]">
                                <input type="text" value="{{ $idf }}" name="idf[]"><br>
                            @endforeach
                        </form>
                    </div>
                </div>
                            
            </div>
        </div>
    </div>
</div>


@endsection