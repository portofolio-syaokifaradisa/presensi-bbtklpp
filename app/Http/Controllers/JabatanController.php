<?php

namespace App\Http\Controllers;

use App\Models\Jabatan;
use Exception;
use Illuminate\Http\Request;

class JabatanController extends Controller
{
    public function index(){
        return view('jabatan.index');
    }

    public function create(){
        return view('jabatan.create');
    }

    public function store(Request $request){
        try{
            Jabatan::create([
                'nama' => $request->nama,
                'kelas' => $request->kelas
            ]);

            return redirect(route('jabatan.index'))->with('success', 'Tambah Data Jabatan Berhasil');
        }catch(Exception $e){
            return back()->with('error', 'Terjadi Kesalahan, Silahkan Coba Lagi!')->withInput();
        }
    }

    public function edit($id){
        $jabatan = Jabatan::find($id);
        return view('jabatan.create', compact('jabatan'));
    }

    public function update(Request $request, $id){
        try{
            Jabatan::find($id)->update([
                'nama' => $request->nama,
                'kelas' => $request->kelas
            ]);

            return redirect(route('jabatan.index'))->with('success', 'Ubah Data Jabatan Berhasil');
        }catch(Exception $e){
            return back()->with('error', 'Terjadi Kesalahan, Silahkan Coba Lagi!')->withInput();
        }
    }

    public function delete($id){
        try{
            Jabatan::find($id)->delete();
            return response()->json([
                'status' => 'success',
                'title' => 'Penghapusan Sukses!',
                'message' => 'Penghapusan Jabatan Sukses!'
            ]);
        }catch(Exception $e){
            return response()->json([
                'status' => 'error',
                'title' => 'Penghapusan Gagal!',
                'message' => 'Penghapusan Jabatan Gagal, Silahkan Coba Lagi!'
            ]);
        }
    }

    public function datatable(Request $request){
        /* ================== [1] Persiapan Pengambilan Data ================== */
        $startNumber = $request->start;
        $rowperpage = $request->length;
        $records = Jabatan::query();

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
        $kelas_search = $request->columns[3]['search']['value'];

        if($nama_search){
            $records = $records->where('nama', 'like', "%{$nama_search}%");
        }

        if($kelas_search){
            $records = $records->where('kelas', 'like', "%{$kelas_search}%");
        }

        /* ================== [4] Pengambilan Data ================== */
        $totalRecordswithFilter = $records->count();
        $totalRecord = Jabatan::count();

        $records = $records->skip($startNumber)->take($rowperpage)->get();

        /* ================== [7] Memformat Data ================== */
        $data_arr = array();
        foreach($records as $index => $record){
            $data_arr[] = array(
                "id" => $record->id,
                "no" => $startNumber + $index + 1,
                "nama" => $record->nama,
                "kelas" => $record->kelas
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
