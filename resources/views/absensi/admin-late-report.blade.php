@extends('absensi.late-report')

@section('pre-extends')
    <table class="border-table" style="margin-bottom: 20px">
        <tr>
            <th class="text-center" style="width: 10px">No</th>
            <th class="text-center">Nama</th>  
            <th class="text-center">NIP</th>
            <th class="text-center">Total Telat</th>
            <th class="text-center">Jam Kerja</th>
        </tr>
        @foreach ($records->pluck('user_id')->unique() as $user_id)
            @php
                $total_late = 0;
                $total_jam_kerja = 0;
                foreach ($records->where('user_id', $user_id) as $record) {
                    $pagi = $record->pagi != "00:00:00" ? date("H:i", strtotime($record->pagi) + 60 * 60) : '';
                    $total_late = $total_late + ($pagi ? $record->minute_late : 0);
                    $total_jam_kerja = $total_jam_kerja + ($pagi ? $record->minute_jam_kerja : 0);
                }
            @endphp
            <tr>
                <td>{{ $loop->index + 1 }}</td>
                <td>{{ $record->user->name }}</td>
                <td>{{ $record->user->nip }}</td>
                <td class="text-center">
                    {{ floor($total_late / 60) . " Jam " . ($total_late % 60) . " Menit" }} <br>
                    {{ $total_late . " Menit" }}
                </td>
                <td class="text-center">
                    {{ floor($total_jam_kerja / 60) . " Jam " . ($total_jam_kerja % 60) . " Menit" }} <br>
                    {{ $total_jam_kerja . " Menit" }}
                </td>
            </tr>
        @endforeach
    </table>
@endsection