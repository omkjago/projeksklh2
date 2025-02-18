@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Riwayat Absensi</h1>

    <!-- Filter hanya untuk Admin -->
    @if(auth()->user()->hasRole('admin'))
        <form method="GET" action="{{ route('riwayat.absensi') }}" class="mb-3">
            <div class="row">
                <div class="col-md-4">
                    <select name="kelas_id" class="form-control" onchange="this.form.submit()">
                        <option value="">-- Pilih Kelas --</option>
                        <option value="guru_admin" {{ $selectedKelas == 'guru_admin' ? 'selected' : '' }}>Guru/Admin</option>
                        @foreach ($kelasList as $kelas)
                            <option value="{{ $kelas->id }}" {{ $selectedKelas == $kelas->id ? 'selected' : '' }}>
                                {{ $kelas->nama_kelas }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
        </form>
    @endif
    <br>
    <div class="overflow-x-auto">
    <table class="table-auto w-full border-collapse border border-gray-300">
    <thead>
    <tr class="bg-gray-200">
    <th>
                    <a href="{{ route('riwayat.absensi', [
                        'orderBy' => 'presensi.name', 
                        'direction' => $direction == 'asc' ? 'desc' : 'asc',
                        'kelas_id' => $selectedKelas
                    ]) }}">
                        Nama
                        @if($orderBy == 'presensi.name')
                            @if($direction == 'asc')
                                <span>&#8593;</span> <!-- Arrow up -->
                            @else
                                <span>&#8595;</span> <!-- Arrow down -->
                            @endif
                        @endif
                    </a>
                </th>
                <th>Kelas</th>
                <th>
                    <a href="{{ route('riwayat.absensi', [
                        'orderBy' => 'presensi.tanggal_presensi', 
                        'direction' => $direction == 'asc' ? 'desc' : 'asc',
                        'kelas_id' => $selectedKelas,
                        'tanggal_presensi' => $selectedTanggal,
                        'jam_in' => $selectedJamMasuk
                    ]) }}">
                        Tanggal Presensi
                        @if($orderBy == 'presensi.tanggal_presensi')
                            @if($direction == 'asc')
                                <span>&#8593;</span> <!-- Arrow up -->
                            @else
                                <span>&#8595;</span> <!-- Arrow down -->
                            @endif
                        @endif
                    </a>
                </th>
                <th>
                    <a href="{{ route('riwayat.absensi', [
                        'orderBy' => 'presensi.jam_in', 
                        'direction' => $direction == 'asc' ? 'desc' : 'asc',
                        'kelas_id' => $selectedKelas,
                        'tanggal_presensi' => $selectedTanggal,
                        'jam_in' => $selectedJamMasuk
                    ]) }}">
                        Jam Masuk
                        @if($orderBy == 'presensi.jam_in')
                            @if($direction == 'asc')
                                <span>&#8593;</span> <!-- Arrow up -->
                            @else
                                <span>&#8595;</span> <!-- Arrow down -->
                            @endif
                        @endif
                    </a>
                </th>
                <th>Jam Keluar</th>
                <th>Foto Masuk</th>
                <th>Foto Keluar</th>
                <th>status</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($riwayat as $presensi)
                <tr class="border border-gray-300 px-4 py-2 text-center">
                    <td>{{ $presensi->name }}</td>
                    <td>
                        @if($presensi->nama_kelas)
                            {{ $presensi->nama_kelas }}
                        @else
                            Guru/Admin
                        @endif
                    </td>
                    <td>{{ date('d M Y', strtotime($presensi->tanggal_presensi)) }}</td>
                    <td>{{ $presensi->jam_in ?? '-' }}</td>
                    <td>{{ $presensi->jam_out ?? '-' }}</td>
                    <td>
                        @if($presensi->foto_in)
                        <img src="{{ asset('storage/uploads/absensi/' . $presensi->foto_in) }}" alt="Foto Masuk" width="90" class="mx-auto">
                        @else
                            -
                        @endif
                    </td>
                    <td>
                        @if($presensi->foto_out)
                            <img src="{{ asset('storage/uploads/absensi/' . $presensi->foto_out) }}" alt="Foto Keluar" width="90" class="mx-auto">
                        @else
                            -
                        @endif
                    </td>
                    <td>{{ $presensi->status }}</td>

                </tr>
            @endforeach
        </tbody>
    </table>
    </div>
</div>
@endsection
