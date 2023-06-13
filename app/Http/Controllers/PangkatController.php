<?php

namespace App\Http\Controllers;

use App\Models\Pangkat;
use Exception;
use Illuminate\Http\Request;

class PangkatController extends Controller
{
    public function index(){
        return view('pangkat.index');
    }

    public function create(){
        return view('pangkat.create');
    }

    public function store(Request $request){
        try{
            Pangkat::create([
                'golongan' => $request->golongan,
                'tmt' => $request->tmt
            ]);

            return redirect(route('pangkat.index'))->with('success', 'Tambah Data Pangkat Berhasil');
        }catch(Exception $e){
            return back()->with('error', 'Terjadi Kesalahan, Silahkan Coba Lagi!')->withInput();
        }
    }

    public function edit($id){
        $pangkat = Pangkat::find($id);
        return view('pangkat.create', compact('pangkat'));
    }

    public function update(Request $request, $id){
        try{
            Pangkat::find($id)->update([
                'golongan' => $request->golongan,
                'tmt' => $request->tmt
            ]);

            return redirect(route('pangkat.index'))->with('success', 'Ubah Data Pangkat Berhasil');
        }catch(Exception $e){
            return back()->with('error', 'Terjadi Kesalahan, Silahkan Coba Lagi!')->withInput();
        }
    }

    public function delete($id){
        try{
            Pangkat::find($id)->delete();
            return response()->json([
                'status' => 'success',
                'title' => 'Penghapusan Sukses!',
                'message' => 'Penghapusan Pangkat Sukses!'
            ]);
        }catch(Exception $e){
            return response()->json([
                'status' => 'error',
                'title' => 'Penghapusan Gagal!',
                'message' => 'Penghapusan Pangkat Gagal, Silahkan Coba Lagi!'
            ]);
        }
    }

    public function datatable(Request $request){
        /* ================== [1] Persiapan Pengambilan Data ================== */
        $startNumber = $request->start;
        $rowperpage = $request->length;
        $records = Pangkat::query();

        /* ================== [2] Sorting Kolom ================== */
        $sortColumnIndex = $request->order[0]['column'];
        $sortColumnName = $request->columns[$sortColumnIndex]['data'];
        $sortType = $request->order[0]['dir'];
        if($sortColumnName === "no"){
            $records = $records->orderBy('golongan', 'ASC');
        }else{
            $records = $records->orderBy($sortColumnName, $sortType);
        }

        $golongan = $request->columns[2]['search']['value'];
        $tmt = $request->columns[3]['search']['value'];

        if($golongan){
            $records = $records->where('golongan', 'like', "%{$golongan}%");
        }

        if($tmt){
            $records = $records->where('tmt', $tmt);
        }

        /* ================== [4] Pengambilan Data ================== */
        $totalRecordswithFilter = $records->count();
        $totalRecord = Pangkat::count();

        $records = $records->skip($startNumber)->take($rowperpage)->get();

        /* ================== [7] Memformat Data ================== */
        $data_arr = array();
        foreach($records as $index => $record){
            $data_arr[] = array(
                "id" => $record->id,
                "no" => $startNumber + $index + 1,
                "golongan" => $record->golongan,
                "tmt" => $record->tmt
            );
        }

        /* ================== [8] Mengirim JSON ================== */
        echo json_encode([
            "draw" => intval($request->draw),
            "iTotalRecords" => $totalRecord,
            "iTotalDisplayRecords" => $totalRecordswithFilter,
            "aaData" => $data_arr
        ]);
    }
}
