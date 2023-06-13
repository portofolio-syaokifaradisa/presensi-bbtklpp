<?php

namespace App\Http\Controllers;

use Exception;
use Carbon\Carbon;
use App\Models\Absensi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;

class DashboardController extends Controller
{
    public function index(){
        if(Auth::user()->role == "superadmin"){
            return redirect(route('absensi.index'));
        }

        $absensi = Absensi::where('tanggal', date('Y-m-d'))->where('user_id', Auth::user()->id)->get()->first();
        return view('dashboard.index', compact('absensi'));
    }

    public function keterangan($type){
        return view('dashboard.keterangan', compact('type'));
    }

    public function store(Request $request, $type){
        try{
            $date = $request->mulai;
            while($date <= $request->selesai){
                if(!in_array(Carbon::parse(date('Y-m-d', strtotime($date)))->format('l'), ["Saturday", 'Sunday'])){
                    Absensi::create([
                        'user_id' => Auth::user()->id,
                        'tanggal' => $date,
                        'status' => $type,
                        'keterangan' => $request->keterangan,
                    ]);
                }
                
                $date = date('Y-m-d', strtotime("+1 day", strtotime($date)));
            }

            return redirect(route('dashboard'))->with('success', "Absen $type Sukses");
        }catch(Exception $e){
            return back()->with('error', 'Terjadi Kesalahan, Silahkan Coba Lagi!');
        }
    }

    public function present(){
        try{
            $absensi_count = Absensi::where('tanggal', date('Y-m-d'))->where('user_id', Auth::user()->id)->count();
            if(!$absensi_count){
                Absensi::create([
                    'user_id' => Auth::user()->id,
                    'pagi' => date("H:i"),
                    'tanggal' => date('Y-m-d'),
                    'status' => 'Hadir'
                ]);
            }else{
                $absensi_user = Absensi::where('tanggal', date('Y-m-d'))->where('user_id', Auth::user()->id)->get()->first();
                if(!$absensi_user->sore){
                    Absensi::where('tanggal', date('Y-m-d'))->where('user_id', Auth::user()->id)->update([
                        'sore' => date("H:i"),
                    ]);
                }
            }

            return redirect(route('dashboard'))->with('success', 'Sukses Melakukan Perekaman Absensi!');
        }catch(Exception $e){
            return back()->with('error', 'Terjadi Kesalahan Silahkan Coba Lagi!');
        }
    }

    private function filteringData($records, $hariSearch, $tanggalSearch, $pagiSearch, $soreSearch, $statusSearch, $keteranganSearch){
        if($hariSearch && $hariSearch != "SEMUA"){
            $records = $records->whereRaw("DAYNAME(tanggal) = '$hariSearch'");
        }

        if($tanggalSearch){
            $dates = explode("|", $tanggalSearch);

            $startDate = $dates[0];
            if($startDate){
                $records = $records->where('tanggal', '>=', $startDate);
            }

            $endDate = $dates[1];
            if($endDate){
                $records = $records->where('tanggal', '<=', $endDate);
            }
        }

        if($pagiSearch){
            $pagiSearch = date('H:i:s', strtotime('-1 hour', strtotime($pagiSearch)));
            $records = $records->where('pagi', '>=', $pagiSearch);
        }

        if($soreSearch){
            $soreSearch = date('H:i:s', strtotime('-1 hour', strtotime($soreSearch)));
            $records = $records->where('sore', '<=', $soreSearch.":00");
        }

        if($statusSearch && $statusSearch != "SEMUA"){
            $records = $records->where('status', $statusSearch);
        }

        if($keteranganSearch){
            $records = $records->where('keterangan', 'like', "%{$keteranganSearch}%");
        }

        return $records;
    }

    public function datatable(Request $request){
        /* ================== [1] Persiapan Pengambilan Data ================== */
        $startNumber = $request->start;
        $rowperpage = $request->length;
        $records = Absensi::where('user_id', Auth::user()->id);

        /* ================== [2] Sorting Kolom ================== */
        $sortColumnIndex = $request->order[0]['column'];
        $sortColumnName = $request->columns[$sortColumnIndex]['data'];
        $sortType = $request->order[0]['dir'];
        if($sortColumnName === "no"){
            $records = $records->orderBy('tanggal', 'DESC');
        }else{
            $records = $records->orderBy($sortColumnName, $sortType);
        }

        /* ================== [3] Individual Search ================== */
        $hariSearch = $request->columns[1]['search']['value'];
        $tanggalSearch = $request->columns[2]['search']['value'];
        $pagiSearch = $request->columns[3]['search']['value'];
        $soreSearch = $request->columns[4]['search']['value'];
        $statusSearch = $request->columns[5]['search']['value'];
        $keteranganSearch = $request->columns[6]['search']['value'];

        $records = $this->filteringData(
            $records,
            $hariSearch,
            $tanggalSearch,
            $pagiSearch,
            $soreSearch,
            $statusSearch,
            $keteranganSearch
        );

        /* ================== [4] Pengambilan Data ================== */
        $totalRecordswithFilter = $records->count();
        $totalRecord = Absensi::where('user_id', Auth::user()->id)->count();
        $records = $records->skip($startNumber)->take($rowperpage)->get();

        /* ================== [7] Memformat Data ================== */
        $data_arr = array();
        foreach($records as $index => $record){
            $date = date('Y-m-d', strtotime($record->tanggal));
            $data_arr[] = array(
                "id" => $record->id,
                "no" => $startNumber + $index + 1,
                "hari" => Carbon::parse($date)->locale('id')->dayName,
                "tanggal" =>  Carbon::parse($date)->translatedFormat('d F Y'),
                "masuk" => $record->pagi != "00:00:00" ? date("H:i", strtotime($record->pagi) + 60 * 60) : '-',
                'keluar' => $record->sore ? date("H:i", strtotime($record->sore) + 60 * 60) : '-',
                'status' => $record->status,
                "keterangan" => $record->keterangan,
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
        $records = Absensi::where('user_id', Auth::user()->id)->orderBy('tanggal', 'ASC');
        $records = $this->filteringData(
            $records,
            $request->hari,
            $request->tanggal_start."|".$request->tanggal_end,
            $request->masuk,
            $request->keluar,
            $request->status,
            $request->keterangan
        )->get();

        $periode = '';

        $startDate = $request->tanggal_start;
        $endDate = $request->tanggal_end;
        if($startDate){
            if($endDate){
                $periode = Carbon::parse($startDate)->translatedFormat('d F Y') . " s/d " . Carbon::parse($endDate)->translatedFormat('d F Y');
            }else{
                $periode = Carbon::parse($startDate)->translatedFormat('d F Y') . " s/d Sekarang";
            }
        }else{
            if($endDate){
                $periode = "Sebelum " . Carbon::parse($endDate)->translatedFormat('d F Y');
            }else{
                $periode = "Keseluruhan";
            }
        }

        $pdf = Pdf::loadView('absensi.report', [
            'reports' => $records,
            'title' => 'Laporan Absensi ' . Auth::user()->name,
            'subtitle' => "Periode " . $periode,
            'role' => Auth::user()->role
        ]);
        return $pdf->stream('Laporan Absensi Pegawai.pdf');
    }

    public function printLate(Request $request){
        $records = Absensi::where('user_id', Auth::user()->id)->orderBy('tanggal', 'ASC');

        if($request->hari && $request->hari != "SEMUA"){
            $records = $records->whereRaw("DAYNAME(tanggal) = '$request->hari'");
        }

        $startDate = $request->tanggal_start;
        if($startDate){
            $records = $records->where('tanggal', '>=', $startDate);
        }

        $endDate = $request->tanggal_end;
        if($endDate){
            $records = $records->where('tanggal', '<=', $endDate);
        }
        
        $records = $records->get();

        $periode = '';

        $startDate = $request->tanggal_start;
        $endDate = $request->tanggal_end;
        if($startDate){
            if($endDate){
                $periode = Carbon::parse($startDate)->translatedFormat('d F Y') . " s/d " . Carbon::parse($endDate)->translatedFormat('d F Y');
            }else{
                $periode = Carbon::parse($startDate)->translatedFormat('d F Y') . " s/d Sekarang";
            }
        }else{
            if($endDate){
                $periode = "Sebelum " . Carbon::parse($endDate)->translatedFormat('d F Y');
            }else{
                $periode = "Keseluruhan";
            }
        }

        $pdf = Pdf::loadView('absensi.late-report', [
            'records' => $records,
            'title' => 'Laporan Absensi Telat ' . Auth::user()->name,
            'subtitle' => "Periode " . $periode,
            'role' => Auth::user()->role
        ]);
        return $pdf->stream('Laporan Absensi Telat Pegawai.pdf');
    }
}
