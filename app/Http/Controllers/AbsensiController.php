<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Absensi;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;

class AbsensiController extends Controller
{
    public function index(){
        return view('absensi.index');
    }

    private function filteringData($records, $pegawaiSearch, $hariSearch, $tanggalSearch, $statusSearch, $pagiSearch, $soreSearch, $keteranganSearch){
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

        if($pegawaiSearch){
            $records = $records->wherehas('user', function($q) use ($pegawaiSearch){
                $q->where('name', 'like', "%{$pegawaiSearch}%");
                $q->orwhere('nip', 'like', "%{$pegawaiSearch}%");
            });
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
        $records = Absensi::query();

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
        $records = $this->filteringData(
            $records,
            $request->columns[1]['search']['value'],
            $request->columns[2]['search']['value'],
            $request->columns[3]['search']['value'],
            $request->columns[4]['search']['value'],
            $request->columns[5]['search']['value'],
            $request->columns[6]['search']['value'],
            $request->columns[7]['search']['value'],
        );

        /* ================== [4] Pengambilan Data ================== */
        $totalRecordswithFilter = $records->count();
        $totalRecord = Absensi::count();
        $records = $records->skip($startNumber)->take($rowperpage)->get();

        /* ================== [7] Memformat Data ================== */
        $data_arr = array();
        foreach($records as $index => $record){
            $data_arr[] = array(
                "id" => $record->id,
                "no" => $startNumber + $index + 1,
                "nama" => $record->user->name,
                "nip" => $record->user->nip ?? '-',
                "hari" => $record->hari,
                "tanggal" =>  $record->tanggal_indo,
                "masuk" => isset($record->pagi) ? date("H:i", strtotime($record->pagi) + 60 * 60) : '-',
                'keluar' => isset($record->sore) ? date("H:i", strtotime($record->sore) + 60 * 60) : '-',
                'status' => $record->status,
                'keterangan' => $record->keterangan,
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
        $records = Absensi::orderBy('tanggal', 'ASC');
        $records = $this->filteringData(
            $records,
            $request->pegawai,
            $request->hari,
            $request->tanggal_start."|".$request->tanggal_end,
            $request->status,
            $request->pagi,
            $request->sore,
            $request->keterangan,
        )->get();

        $periode = '';

        $startDate = $request->tanggal_start;
        $endDate = $request->tanggal_end;
        if($startDate){
            if($endDate){
                $periode = Carbon::parse($startDate)->translatedFormat('d F Y') . " s/d " . Carbon::parse($endDate)->translatedFormat('d F Y');
            }else{
                $periode = Carbon::parse($startDate)->translatedFormat('d F Y') . " s/d " . Carbon::parse(now())->translatedFormat('d F Y');
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
            'title' => 'Laporan Absensi Pegawai',
            'subtitle' => 'Periode ' . $periode,
            'role' => Auth::user()->role
        ]);
        return $pdf->stream('Laporan Absensi Pegawai.pdf');
    }

    public function summary(Request $request){
        $records = Absensi::with('user')->orderBy('tanggal');

        $startDate = $request->tanggal_start;
        if($startDate){
            $records = $records->where('tanggal', '>=', $startDate);
        }

        $endDate = $request->tanggal_end;
        if($endDate){
            $records = $records->where('tanggal', '<=', $endDate);
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
                    'name' => $absen->user->name,
                    'nip' => $absen->user->nip,
                ];
            }
        }

        $periode = '';

        $startDate = $request->tanggal_start;
        $endDate = $request->tanggal_end;
        if($startDate){
            if($endDate){
                $periode = Carbon::parse($startDate)->translatedFormat('d F Y') . " s/d " . Carbon::parse($endDate)->translatedFormat('d F Y');
            }else{
                $periode = Carbon::parse($startDate)->translatedFormat('d F Y') . " s/d " . Carbon::parse(now())->translatedFormat('d F Y');
            }
        }else{
            if($endDate){
                $periode = "Sebelum " . Carbon::parse($endDate)->translatedFormat('d F Y');
            }else{
                $periode = "Keseluruhan";
            }
        }

        $data = collect($data)->sortBy('name');
        $pdf = Pdf::loadView('absensi.summary', [
            'absensi' => $data,
            'title' => 'Laporan Ringkasan Absensi Pegawai',
            'subtitle' => "Periode " . $periode
        ]);
        return $pdf->stream('Laporan Ringkasan Absensi Pegawai.pdf');
    }

    public function printLate(Request $request){
        $records = Absensi::orderBy('tanggal', 'ASC');

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
        
        if($request->pegawai){
            $records = $records->wherehas('user', function($q) use ($request){
                $q->where('name', 'like', "%{$request->pegawai}%");
                $q->orwhere('nip', 'like', "%{$request->pegawai}%");
            });
        }

        $records = $records->get();

        $periode = '';

        $startDate = $request->tanggal_start;
        $endDate = $request->tanggal_end;
        if($startDate){
            if($endDate){
                $periode = Carbon::parse($startDate)->translatedFormat('d F Y') . " s/d " . Carbon::parse($endDate)->translatedFormat('d F Y');
            }else{
                $periode = Carbon::parse($startDate)->translatedFormat('d F Y') . " s/d " . Carbon::parse(now())->translatedFormat('d F Y');
            }
        }else{
            if($endDate){
                $periode = "Sebelum " . Carbon::parse($endDate)->translatedFormat('d F Y');
            }else{
                $periode = "Keseluruhan";
            }
        }

        $pdf = Pdf::loadView('absensi.admin-late-report', [
            'records' => $records,
            'title' => 'Laporan Absensi Telat',
            'subtitle' => "Periode " . $periode,
            'role' => Auth::user()->role
        ]);
        return $pdf->stream('Laporan Absensi Telat Pegawai.pdf');
    }
}
