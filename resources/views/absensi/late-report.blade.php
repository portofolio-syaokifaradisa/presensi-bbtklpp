@extends('templates.report')

@section('content')
    @yield('pre-extends')
    <table class="border-table">
        <tr>
            <th class="text-center" style="width: 10px">No</th>
            @if($role != "user")
                <th class="text-center">Pegawai</th>  
            @endif
            <th class="text-center">Tanggal</th>
            <th class="text-center">Jam</th>
            <th class="text-center">Telat Pagi</th>
            <th class="text-center">Jam Kerja</th>
        </tr>
        @php
            $total_late = 0;
            $total_jam_kerja = 0;
        @endphp
        @foreach ($records as $index => $record)
            @php
                $pagi = $record->pagi ? date("H:i", strtotime($record->pagi) + 60 * 60) : '';
                $sore = $record->sore ? date("H:i", strtotime($record->sore) + 60 * 60) : '';

                $total_late = $total_late + $record->minute_late;
                $total_jam_kerja = $total_jam_kerja + $record->minute_jam_kerja;
            @endphp
            <tr>
                <td class="text-center">{{ $index + 1 }}</td>
                @if($role != "user")
                    <td>
                        {{ $record->user->name }} <br>
                        {{ $record->user->nip }}
                    </td>
                @endif
                <td class="text-center">{{ $record->tanggal_indo }}</td>
                <td class="text-center">
                    @if($pagi)
                        {{ $pagi }}
                        -
                        {{ $sore }}
                    @else
                        {{ $record->status }}
                    @endif
                </td>
                <td class="text-center">
                    {{ $record->late }}
                </td>
                <td class="text-center">
                    {{ $record->jam_kerja }}
                </td>
            </tr>
        @endforeach
        <tr>
            <td colspan="{{ $role != "user" ? 4 : 3 }}">
                Total
            </td>
            <td class="text-center">
                {{ floor($total_late / 60) . " Jam " . ($total_late % 60) . " Menit"  }} <br>
                ({{ $total_late . " Menit" }} )
            </td>
            <td class="text-center">
                {{ floor($total_jam_kerja / 60) . " Jam " . ($total_jam_kerja % 60) . " Menit"  }} <br>
                ({{ $total_jam_kerja . " Menit" }} )
            </td>
        </tr>
    </table>
@endsection