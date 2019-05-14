@extends('layouts.app2')

@section('content')
<div class="container">
    <div class="wrapper wrapper-content">
        <div class="ibox-content">
            <div class="row">
                <table class="table table-responsive">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Token</th>
                            @foreach($jurnal as $jur )
                            <th>D {{ $jur->id }}</th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>         
                        @foreach($token as $tok)
                        <tr>
                            <td>{{ $tok->id_token }}</td>
                            <td>{{ $tok->token }}</td>
                            @foreach($jurnal as $jur )
                            <td>{{ $jur->id }}</td>
                            @endforeach
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
