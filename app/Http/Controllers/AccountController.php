<?php

namespace App\Http\Controllers;

use App\Http\Requests\AccountRequest;
use App\Models\Absensi;
use App\Models\Jabatan;
use App\Models\Pangkat;
use App\Models\Status;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class AccountController extends Controller
{
    public function index(){
        $status = Status::orderBy('nama')->get();
        return view('account.index', compact('status'));
    }

    public function create(){
        $jabatan = Jabatan::orderBy('nama')->get();
        $pangkat = Pangkat::orderBy('golongan')->get();
        $status = Status::orderBy('nama')->get();
        return view('account.create', compact('jabatan', 'pangkat', 'status'));
    }

    public function store(AccountRequest $request){
        try{
            User::create([
                'name' => $request->name,
                'nip' => $request->nip,
                'nik' => $request->nik,
                'pendidikan' => $request->pendidikan,
                'gelar' => $request->gelar,
                'tanggal_lahir' => $request->tanggal_lahir,
                'alamat' => $request->alamat,
                'jabatan_id' => $request->jabatan_id,
                'pangkat_id' => $request->pangkat_id,
                'status_id' => $request->status_id,
                'email' => $request->email,
                'password' => bcrypt($request->password),
                'role' => Auth::user()->role == "admin" ? "user" : $request->role,
            ]);

            return redirect(route('account.index'))->with('success', 'Penambahan Akun Sukses');
        }catch(Exception $e){
            return back()->withInput()->with('error', 'Terjadi Kesalahan, Silahkan Coba Lagi!');
        }
    }

    public function edit($id){
        $account = User::find($id);
        $jabatan = Jabatan::orderBy('nama')->get();
        $pangkat = Pangkat::orderBy('golongan')->get();
        $status = Status::orderBy('nama')->get();
        return view('account.create', compact('jabatan', 'pangkat', 'status', 'account'));
    }

    public function update(AccountRequest $request, $id){
        try{
            $user = User::find($id);
            if($request->password){
                if(password_verify($request->old_password, $user->password)){
                    $user->password = bcrypt($request->password);
                }else{
                    return back()->withInput()->with('error', 'Password Lama Tidak Sama Dengan Sebelumnya!');
                }
            }
            $user->name = $request->name;
            $user->nip = $request->nip;
            $user->email = $request->email;
            $user->nik = $request->nik;
            $user->pendidikan = $request->pendidikan;
            $user->gelar = $request->gelar;
            $user->tanggal_lahir = $request->tanggal_lahir;
            $user->alamat = $request->alamat;
            $user->jabatan_id = $request->jabatan_id;
            $user->pangkat_id = $request->pangkat_id;
            $user->status_id = $request->status_id;
            $user->role = Auth::user()->role == "admin" ? "user" : $request->role;
            $user->save();
            return redirect(route('account.index'))->with('success', 'Perubahan Data Akun Sukses');
        }catch(Exception $e){
            return back()->withInput()->with('error', 'Terjadi Kesalahan, Silahkan Coba Lagi!');
        }
    }

    public function delete($id){
        try{
            if(!in_array(Auth::user()->role, ["admin", 'superadmin'])){
                return response()->json([
                    'status' => 'error',
                    'title' => 'Akses Ditolak!',
                    'message' => 'Anda Tidak Memiliki Akses!'
                ]);
            }

            DB::beginTransaction();
            Absensi::where('user_id', $id)->delete();
            User::find($id)->delete();
            DB::commit();

            return response()->json([
                'status' => 'success',
                'title' => 'Penghapusan Sukses!',
                'message' => 'Penghapusan AKun Sukses!'
            ]);
        }catch(Exception $e){
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'title' => 'Penghapusan Gagal!',
                'message' => 'Penghapusan AKun Gagal, Silahkan Coba Lagi!'
            ]);
        }
    }

    private function filterData($records, $nama_search, $identitas_search, $jabatanSearch, $statusSearch, $roleSearch){
        if($nama_search){
            $records = $records->where('name', 'like', "%{$nama_search}%");
            $records = $records->orWhere('gelar', 'like', "%{$nama_search}%");
            $records = $records->orWhere('nip', 'like', "%{$nama_search}%");
            $records = $records->orWhere('email', 'like', "%{$nama_search}%");
        }
        
        if($identitas_search){
            $records = $records->where('gender', 'like', "%{$identitas_search}%");
            $records = $records->orWhere('alamat', 'like', "%{$identitas_search}%");
            $records = $records->orWhere('nik', 'like', "%{$identitas_search}%");
            $records = $records->orWhere('tanggal_lahir', 'like', "%{$identitas_search}%");
            $records = $records->orWhere('pendidikan', 'like', "%{$identitas_search}%");
        }

        if($jabatanSearch){
            $records = $records->whereHas('jabatan', function($q) use ($jabatanSearch){
                $q->where('nama', 'like', "%{$jabatanSearch}%");
                $q->orWhere('kelas', 'like', "%{$jabatanSearch}%");
            });

            $records = $records->orWhereHas('pangkat', function($q) use ($jabatanSearch){
                $q->where('golongan', 'like', "%{$jabatanSearch}%");
            });
        }

        if($statusSearch && $statusSearch != "SEMUA"){
            $records = $records->where('status_id', $statusSearch);
        }

        if(Auth::user()->role == 'superadmin'){
            if($roleSearch && $roleSearch != "Semua"){
                $records = $records->where('role', $roleSearch);
            }else{
                $records = $records->where(function($q){
                    return $q->where('role', 'user')->orWhere('role', 'admin');
                });
            }
        }else{
            $records = $records->where('role', 'user');
        }

        return $records;
    }

    public function datatable(Request $request){
        /* ================== [1] Persiapan Pengambilan Data ================== */
        $startNumber = $request->start;
        $rowperpage = $request->length;
        $records = User::query();

        /* ================== [2] Sorting Kolom ================== */
        $sortColumnIndex = $request->order[0]['column'];
        $sortColumnName = $request->columns[$sortColumnIndex]['data'];
        $sortType = $request->order[0]['dir'];
        if($sortColumnName === "no"){
            $records = $records->orderBy('name', 'ASC');
        }else{
            $records = $records->orderBy($sortColumnName, $sortType);
        }

        /* ================== [3] Individual Search ================== */
        $nama_search = $request->columns[2]['search']['value'];
        $identitas_search = $request->columns[3]['search']['value'];
        $jabatan_search = $request->columns[4]['search']['value'];
        $status_search = $request->columns[5]['search']['value'];
        $records = $this->filterData(
            $records,
            $nama_search, 
            $identitas_search,
            $jabatan_search,
            $status_search,
            Auth::user()->role == 'superadmin' ? $request->columns[6]['search']['value'] : null
        );
        
        /* ================== [4] Pengambilan Data ================== */
        $totalRecordswithFilter = $records->count();
        $totalRecord = User::where('role', 'user');
        if(Auth::user()->role == 'superadmin'){
            $totalRecord->orWhere('role', 'admin');
        }
        $totalRecord = $totalRecord->count();

        $records = $records->skip($startNumber)->take($rowperpage)->get();

        /* ================== [7] Memformat Data ================== */
        $data_arr = array();
        foreach($records as $index => $record){
            $data_arr[] = array(
                "id" => $record->id,
                "no" => $startNumber + $index + 1,
                "name" => $record->name,
                "gelar" => $record->gelar,
                "nip" => $record->nip,
                "email" => $record->email,
                'role' => $record->role,
                "jabatan" => $record->jabatan->nama . " Kelas " . $record->jabatan->kelas,
                "pangkat" => "Golongan " . $record->pangkat->golongan,
                'status' => $record->status->nama,
                'jenis_kelamin' => $record->gender,
                'nik' => $record->nik,
                'alamat' => $record->alamat,
                'tanggal_lahir' => $record->tanggal_lahir,
                'pendidikan' => $record->pendidikan,
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

    public function print(Request $request){
        $records = User::orderBy('name', 'ASC');
        $records = $this->filterData(
            $records,
            $request->name, 
            $request->identitas, 
            $request->jabatan, 
            $request->status, 
            Auth::user()->role == 'superadmin' ? $request->role : null
        )->get();

        $pdf = Pdf::loadView('account.report', [
            'accounts' => $records,
            'title' => 'Laporan Akun Pegawai',
            'subtitle' => '',
            'role' => Auth::user()->role
        ]);
        return $pdf->stream('Laporan Akun Pegawai.pdf');
    }

    public function summary(Request $request){
        $records = Absensi::with('user')->orderBy('tanggal');

        if($request->tanggal){
            $dates = explode("-", $request->tanggal);
            $records = $records->whereYear('tanggal', $dates[0]);
            $records = $records->whereMonth('tanggal', $dates[1]);
        }

        $absensi = $records->get();
        $data = [];

        foreach($absensi as $absen){
            if(isset($data[$absen->user_id])){
                if(isset($data[$absen->user_id][$absen->status])){
                    $data[$absen->user_id][$absen->status]++;
                }else{
                    $data[$absen->user_id][$absen->status] = 1;
                }
            }else{
                $data[$absen->user_id] = [
                    $absen->status => 1,
                    'nama' => $absen->user->nama,
                    'nip' => $absen->user->nip,
                ];
            }
        }

        $pdf = Pdf::loadView('absensi.summary', ['absensi' => $data]);
        return $pdf->stream('Laporan Ringkasan Absensi Pegawai.pdf');
    }
}