<?php

namespace App\Http\Controllers;

use App\Models\Status;
use Exception;
use Illuminate\Http\Request;

class StatusPegawaiController extends Controller
{
    public function index(){
        return view('status.index');
    }

    public function create(){
        return view('status.create');
    }

    public function store(Request $request){
        try{
            Status::create([
                'nama' => $request->nama
            ]);

            return redirect(route('status.index'))->with('success', 'Tambah Data Status Berhasil');
        }catch(Exception $e){
            return back()->with('error', 'Terjadi Kesalahan, Silahkan Coba Lagi!')->withInput();
        }
    }

    public function edit($id){
        $status = Status::find($id);
        return view('status.create', compact('status'));
    }

    public function update(Request $request, $id){
        try{
            Status::find($id)->update([
                'nama' => $request->nama
            ]);

            return redirect(route('status.index'))->with('success', 'Ubah Data Status Berhasil');
        }catch(Exception $e){
            return back()->with('error', 'Terjadi Kesalahan, Silahkan Coba Lagi!')->withInput();
        }
    }

    public function delete($id){
        try{
            Status::find($id)->delete();
            return response()->json([
                'status' => 'success',
                'title' => 'Penghapusan Sukses!',
                'message' => 'Penghapusan Status Sukses!'
            ]);
        }catch(Exception $e){
            return response()->json([
                'status' => 'error',
                'title' => 'Penghapusan Gagal!',
                'message' => 'Penghapusan Status Gagal, Silahkan Coba Lagi!'
            ]);
        }
    }

    public function datatable(Request $request){
        /* ================== [1] Persiapan Pengambilan Data ================== */
        $startNumber = $request->start;
        $rowperpage = $request->length;
        $records = Status::query();

        /* ================== [2] Sorting Kolom ================== */
        $sortColumnIndex = $request->order[0]['column'];
        $sortColumnName = $request->columns[$sortColumnIndex]['data'];
        $sortType = $request->order[0]['dir'];
        if($sortColumnName === "no"){
            $records = $records->orderBy('nama', 'ASC');
        }else{
            $records = $records->orderBy($sortColumnName, $sortType);
        }

        $nama_search = $request->columns[2]['search']['value'];
        if($nama_search){
            $records = $records->where('nama', 'like', "%{$nama_search}%");
        }

        /* ================== [4] Pengambilan Data ================== */
        $totalRecordswithFilter = $records->count();
        $totalRecord = Status::count();

        $records = $records->skip($startNumber)->take($rowperpage)->get();

        /* ================== [7] Memformat Data ================== */
        $data_arr = array();
        foreach($records as $index => $record){
            $data_arr[] = array(
                "id" => $record->id,
                "no" => $startNumber + $index + 1,
                "nama" => $record->nama
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
