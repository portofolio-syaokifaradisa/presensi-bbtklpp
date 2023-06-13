@extends("templates.report")

@section('content')
    <table class="border-table">
        <tr>
            <th class="text-center" style="width: 10px">No</th>
            @if($role != "user")
                <th class="text-center">Pegawai</th>  
            @endif
            <th class="text-center">Hari</th>
            <th class="text-center">Tanggal</th>
            <th class="text-center">Jam</th>
            <th class="text-center">Status</th>
            <th class="text-center">Keterangan</th>
        </tr>
        @foreach ($reports as $index => $report)
            <tr>
                <td class="text-center">{{ $index + 1 }}</td>
                @if($role != "user")
                    <td>
                        {{ $report->user->name }} <br>
                        {{ $report->user->nip }}
                    </td>
                @endif
                <td class="text-center">{{ $report->hari }}</td>
                <td class="text-center">{{ $report->tanggal_indo }}</td>
                <td class="text-center">
                    {{ $report->pagi != "00:00:00" ? date("H:i", strtotime($report->pagi) + 60 * 60) : '' }}
                    -
                    {{ $report->sore != "00:00:00" ? date("H:i", strtotime($report->sore) + 60 * 60) : '' }}
                </td>
                <td class="text-center">{{ $report->status }}</td>
                <td>{{ $report->keterangan ?? '-' }}</td>
            </tr>
        @endforeach
    </table>
@endsection