@extends('templates.app')

@section('content')
    @if (Session::has('success'))
        <div class="alert alert-success mb-2">{{ Session::get('success') }}</div>
    @endif
    <div class="card">
        <div class="card-header row">
            <h4 class="col">Tabel Absensi Pribadi</h4>
        </div>
        <div class="card-body">
            @if(Auth::user()->role != "superadmin")
                <div class="row">
                    <div class="col">
                        <a class="btn btn-outline-primary float-left mr-2" href="" id="print-late">
                            <i class="fas fa-print mr-2"></i> Laporan Telat
                        </a>
                        <a class="btn btn-outline-primary float-left" href="" id="print">
                            <i class="fas fa-print mr-2"></i> Laporan
                        </a>
                    </div>
                    <div class="col">
                        @if(!$absensi)
                            <a class="btn btn-outline-primary float-right" href="{{ route('keterangan.index', ['type' => 'Dinas Luar']) }}">
                                <i class="fas fa-pen-nib mr-2"></i> Dinas Luar
                            </a>
                            <a class="btn btn-outline-primary float-right mr-2" href="{{ route('keterangan.index', ['type' => 'Cuti']) }}">
                                <i class="fas fa-pen-nib mr-2"></i> Cuti
                            </a>
                            <a class="btn btn-outline-primary float-right mr-2" href="{{ route('keterangan.index', ['type' => 'Izin']) }}">
                                <i class="fas fa-pen-nib mr-2"></i> Izin
                            </a>
                            <a class="btn btn-outline-primary float-right mr-2" href="{{ route('present') }}">
                                <i class="fas fa-pen-nib mr-2"></i> Absen Pagi
                            </a>
                        @endif
                        @if(($absensi->pagi ?? false) && !($absensi->sore ?? false))
                            <a class="btn @if($absensi->sore || !$absensi->pagi) btn-disabled disabled @else btn-outline-primary float-right @endif" href="{{ route('present') }}">
                                @if(!$absensi->sore || !$absensi->pagi) <i class="fas fa-pen-nib"></i> @endif 
                                Absen Sore
                            </a>
                        @endif
                    </div>
                </div>
            @endif
            <div class="table-responsive pt-3">
                <table class="table table-bordered w-100" id="datatable">
                    <thead>
                        <tr>
                            <th class="text-center" style="width: 15px">No</th>
                            <th class="text-center">Hari</th>
                            <th class="text-center">Tanggal</th>
                            <th class="text-center">Jam Datang</th>
                            <th class="text-center">Jam Pulang</th>
                            <th class="text-center">Status</th>
                            <th class="text-center">Keterangan</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                    <tfoot>
                        <th id="no">No.</th>
                        <th id="hari">hari</th>
                        <th id="tanggal">Tanggal</th>
                        <th id="masuk">Jam Datang</th>
                        <th id="keluar">Jam Pulang</th>
                        <th id="status">Status</th>
                        <th id="keterangan">Keterangan</th>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
@endsection

@section('js-extends')
    <script src="{{ asset('js/dashboard.js') }}"></script>
@endsection