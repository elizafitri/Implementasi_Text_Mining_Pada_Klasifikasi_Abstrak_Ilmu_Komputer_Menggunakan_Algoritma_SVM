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
                    <h2>Input Token</h2>
                    <div class="ibox-content">
                        <p>{{ $abstrak->preproses}}</p>
                        <form action="{{ route('bobot.save') }}" method="post">
                        {{ csrf_field() }}
                            <button class="btn btn-success">Simpan</button><hr>
                            @foreach($token as $item)
                                <?php 
                                    $to = array_keys($tokenisasi, $item->token);
                                    $too = implode(",", $to);
                                    $count = count($to);
                                    // $to = array_keys($tokenn, $item->token);
                                    // $too = implode(",", $to);
                                    // $count = count($to);
                                    // // $tf = ($count / $jumlah_term);
                                ?> 
                                @if ($too != null)
                                    <input type="text" value="{{ $item->id }}" name="id_term[]"> 
                                    <input type="text" value="{{ $abstrak->id }}" name="id_doc[]"> 
                                    <input type="text" value="{{ $too }}" name="indeks[]">
                                    <input type="text" value="{{ $count }}" name="tf[]">
                                    <input type="text" value="" name="df[]">
                                @endif
                            @endforeach
                        </form>
                    </div>
                </div>            
            </div>
        </div>
    </div>
</div>


@endsection