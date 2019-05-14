<!doctype html>
<html lang="en">
    <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <title>VECTOR || BERHASIL</title>

    </head>  
    <body>
    <form action="{{ route('savearray') }}" method="post">
    @csrf
    <button type="submit">Simpan Vector</button>
    <table>
        <thead>
        <tr>
            <th>No</th>
            <th>Term</th>
            <th>Vector</th>
        </tr>
            <thead>
            <tbody>
                <?php $no=0; ?>
                <?php $count = 0; ?>
                @foreach ($jajal as $item)
                    <?php $no++ ?>
                    <tr>
                        <td>{{$no}}</td>
                        <td>{{$item->token}}</td>
                        <td> 
                        <input type="text" name="id_term[]" value="{{ $item->id_token }}">
                        <textarea name="vector_doc[]">
                            @foreach($jurnal as $jur)
                            <?php
                                    $abstrak = $jur->preproses;
                                    $token = explode(" ", $abstrak);  
                                    $id =$jur->id;
                                    $data = array_unique($token);
                                    $tooo = array_keys($data, $item->token);
                                    $to = array_keys($token, $item->token);
                                    $too = implode(",", $to);
                            ?>

                            <?php $df = 0 ?>
                                @for($i = 0 ; $i < count($tooo); $i++)
                                D{{$id}}:[{{ $too }}];
                                    @endfor
                            @endforeach
                            </textarea>
                            {{-- <input type="text" value="{{ $df  }}"> --}}
                        </td>
                    </tr>
                @endforeach
            </tbody>
            </thead>
        </thead>
    </table>
    </form>
    </body>
</html>