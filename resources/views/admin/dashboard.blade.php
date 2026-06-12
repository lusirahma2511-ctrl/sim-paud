@extends('layouts.adminlte')
@section('title', 'Dashboard Admin')
@section('content')

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<div class="container-fluid">

    <!-- ACTION BUTTON -->
    <div class="row mb-3">
        <div class="col-md-12 d-flex gap-2">
            <a href="{{ route('admin.hari_libur.index') }}" class="btn btn-danger btn-sm">
                <i class="fas fa-calendar-times mr-1"></i> Hari Libur
            </a>
        </div>
    </div>

    <!-- STATISTIK -->
    <div class="row">

        <div class="col-md-3 col-6 mb-3">
            <div class="small-box bg-info shadow-sm">
                <div class="inner">
                    <h3>{{ $totalSiswa }}</h3>
                    <p>Total Siswa</p>
                </div>
                <div class="icon"><i class="fas fa-users"></i></div>
            </div>
        </div>

        <div class="col-md-3 col-6 mb-3">
            <div class="small-box bg-success shadow-sm">
                <div class="inner">
                    <h3>{{ $totalGuru }}</h3>
                    <p>Total Guru</p>
                </div>
                <div class="icon"><i class="fas fa-chalkboard-teacher"></i></div>
            </div>
        </div>

        <div class="col-md-3 col-6 mb-3">
            <div class="small-box bg-warning shadow-sm">
                <div class="inner">
                    <h3>{{ $totalKelas }}</h3>
                    <p>Total Kelas</p>
                </div>
                <div class="icon"><i class="fas fa-school"></i></div>
            </div>
        </div>

        <div class="col-md-3 col-6 mb-3">
            <div class="small-box bg-danger shadow-sm">
                <div class="inner">
                    <h3>{{ $totalHadirHariIni ?? 0 }}</h3>
                    <p>Hadir Hari Ini</p>
                </div>
                <div class="icon"><i class="fas fa-user-check"></i></div>
            </div>
        </div>

    </div>

    <!-- GRAFIK + KALENDER -->
    <div class="row mt-2">

        <div class="col-lg-8 mb-3">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white py-2">
                    <strong>Kehadiran Mingguan</strong>
                </div>
                <div class="card-body">
                    <canvas id="absensiChart"></canvas>
                </div>
            </div>
        </div>

        <div class="col-lg-4 mb-3">
            <div class="card shadow-sm border-0 text-center">
                <div class="card-header bg-white py-2">
                    <strong>Kalender</strong>
                </div>
                <div class="card-body py-4">
                    <h2 class="mb-1">{{ date('d') }}</h2>
                    <p class="text-muted mb-0">{{ date('l, d M Y') }}</p>
                </div>
            </div>
        </div>

    </div>

    <!-- INFO TAMBAHAN -->
    <div class="row">

        <div class="col-lg-6 mb-3">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white py-2">
                    <strong>Guru Aktif Hari Ini</strong>
                </div>
                <div class="card-body">

                    @forelse($guruAktif ?? [] as $guru)
                        <div class="d-flex align-items-center mb-2">
                            <i class="fas fa-user text-success mr-2"></i>
                            <span>{{ $guru }}</span>
                        </div>
                    @empty
                        <p class="text-muted">Belum ada guru aktif</p>
                    @endforelse

                </div>
            </div>
        </div>

        <div class="col-lg-6 mb-3">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white py-2">
                    <strong>Aktivitas Terbaru</strong>
                </div>
                <div class="card-body">

                    @forelse($aktivitas ?? [] as $item)
                        <div class="border-bottom mb-2 pb-2">
                            <small>{{ $item }}</small><br>
                            <small class="text-muted">Baru saja</small>
                        </div>
                    @empty
                        <p class="text-muted">Belum ada aktivitas</p>
                    @endforelse

                </div>
            </div>
        </div>

    </div>

</div>

<script>
new Chart(document.getElementById('absensiChart'), {
    type: 'line',
    data: {
        labels: {!! json_encode($hari ?? ['Sen','Sel','Rab','Kam','Jum']) !!},
        datasets: [{
            label: 'Hadir',
            data: {!! json_encode($hadirMingguan ?? [0,0,0,0,0]) !!},
            borderColor: '#28a745',
            fill: false,
            tension: 0.3
        }]
    }
});
</script>

@endsection