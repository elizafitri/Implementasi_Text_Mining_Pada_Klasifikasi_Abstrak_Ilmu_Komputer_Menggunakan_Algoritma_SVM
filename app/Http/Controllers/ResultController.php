<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Klasifikasi;

class ResultController extends Controller
{
    
    //fungsi untuk menampilkan hasil pencarian (belum fix)
    public function result()
    {
        // mengambil data dari tabel klasifikasis
		$klasifikasis = DB::table('klasifikasis')->paginate(5);
 
        // mengirim data abstrak ke view result
        return view('result',[
            'klasifikasis' => $klasifikasis]);

        //  return view('result');
    }

    //fungsi untuk menampilkan hasil pencarian berdasarkan kata kunci yang diinputkan
    public function search(Request $request)
    {
        
        $cari = $request->cari; // kata kunci yang diinputkan
        // mengambil data dari table klasifikasis sesuai kata kunci pencarian
        $klasifikasis = DB::table('klasifikasis')
        ->where('abstrak','like',"%".$cari."%")
        ->paginate(5);
        $klasifikasis->appends($request->only('cari'));
        $hasil=count($klasifikasis); //menghitung jumlah jurnal tempat kata kunci relevan ditemukan

        // $klasifikasi=strtolower($klasifikasis);

        // mengirim data klasifikasis ke result.blade
        return view('result',['klasifikasis' => $klasifikasis, 'cari' => $cari, 'hasil'=>$hasil]);
    }

    // public function coba()
    // {
    //     //Case Folding abstrak
    //     $klasifikasis = strtolower($klasifikasis);
    //     return view('result',['klasifikasis' => $klasifikasis]);
    // }

    

    public function getIndex() {
        $collection = array(
                1 => 'this string is a short string but a good string',
                2 => 'this one isnt quite like the rest but is here',
                3 => 'this is a different short string that not as short'
        );

        $dictionary = array();
        $docCount = array();

        foreach($collection as $docID => $doc) {
                $terms = explode(' ', $doc);
                $docCount[$docID] = count($terms);

                foreach($terms as $term) {
                        if(!isset($dictionary[$term])) {
                                $dictionary[$term] = array('df' => 0, 'postings' => array());
                        }
                        if(!isset($dictionary[$term]['postings'][$docID])) {
                                $dictionary[$term]['df']++;
                                $dictionary[$term]['postings'][$docID] = array('tf' => 0);
                        }

                        $dictionary[$term]['postings'][$docID]['tf']++;
                }
        }

        return array('docCount' => $docCount, 'dictionary' => $dictionary);
    }

    public function getTfidf($term) {
        $index = new getIndex();
        $docCount = count($index['docCount']);
        $entry = $index['dictionary'][$term];
        foreach($entry['postings'] as  $docID => $postings) {
                echo "Document $docID and term $term give TFIDF: " .
                        ($postings['tf'] * log($docCount / $entry['df'], 2));
                echo "\n";
        }
    }

    public function cosineSim($docA, $docB) {
        $result = 0;
        foreach($docA as $key => $weight) {
                $result += $weight * $docB[$key];
        }
        return $result;
    }

    public function cobaa()
    {
        $query = array('is', 'short', 'string');

        $index = new getIndex();
        $matchDocs = array();
        $docCount = count($index['docCount']);

        foreach($query as $qterm) {
            $entry = $index['dictionary'][$qterm];
                foreach($entry['postings'] as $docID => $posting) {
                    $matchDocs[$docID] +=
                        $posting['tf'] *
                        log($docCount + 1 / $entry['df'] + 1, 2);
            }
        }

        // length normalise
        foreach($matchDocs as $docID => $score) {
            $matchDocs[$docID] = $score/$index['docCount'][$docID];
        }

        arsort($matchDocs); // high to low

        var_dump($matchDocs);
        
    }
}
