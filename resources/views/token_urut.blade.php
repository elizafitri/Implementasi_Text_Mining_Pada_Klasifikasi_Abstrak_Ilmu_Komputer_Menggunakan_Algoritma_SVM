@extends('layouts.app2')

@section('content')
<div class="container">
    <div class="wrapper wrapper-content">
        <div class="ibox-content">
            <div class="row">
                <form action="{{ route('create.token.urut') }}" method="post">
                {{ csrf_field() }}
                    <button type="submit" class="btn btn-success">Simpan</button><br>
                    <hr>
                    @foreach ( $sorted as $sort)
                    <input type="text" name="token_urut[]" value="{{ $sort->token }}">
                    @endforeach

                </form>
            </div>
        </div>
    </div>
</div>
@endsection
