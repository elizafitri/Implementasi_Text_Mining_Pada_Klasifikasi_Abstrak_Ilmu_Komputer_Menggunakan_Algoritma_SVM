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
                    <h2>Menghitung Nilai DF</h2>
                    <div class="ibox-content">
                        
                        <form action="{{ route('save.df') }}" method="post">
                        {{ csrf_field() }}
                            <button class="btn btn-success">Simpan</button><hr>
                            @foreach ($term as $ter)
                                <?php $tf = $ter->id_term;?>
                                <?php $df = \App\tf::where('id_term', $tf)->get(); ?>
                
                                <input type="text" value="{{ $ter->id}}" name="id_term[]">
                                <input type="text" value="{{ $df->count() }}" name="df[]"><br>
                                
                            @endforeach
                        </form>
                    </div>
                </div>
                            
            </div>
        </div>
    </div>
</div>


@endsection