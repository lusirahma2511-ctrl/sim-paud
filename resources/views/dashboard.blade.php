@extends('layouts.adminlte')
@section('title', 'Welcome Admin')

@section('content')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<div class="row">

    <!-- Total Siswa -->
    <div class="col-lg-3 col-12">
        <div class="small-box bg-blue">
            <div class="inner">
                <h3>{{ $totalSiswa }}</h3>
                <p>Total Siswa</p>
            </div>
            <div class="icon">
                <i class="fas fa-users" style="color:#E91E63;"></i>
            </div>
        </div>
    </div>

    <!-- Total Guru -->
    <div class="col-lg-3 col-12">
        <div class="small-box bg-purple">
            <div class="inner">
                <h3>{{ $totalGuru }}</h3>
                <p>Total Guru</p>
            </div>
            <div class="icon">
                <i class="fas fa-chalkboard-teacher" style="color:#E91E63"></i>
            </div>
        </div>
    </div>

    <!-- Total Kelas -->
    <div class="col-lg-3 col-12">
        <div class="small-box bg-pink">
            <div class="inner">
                <h3>{{ $totalKelas }}</h3>
                <p>Total Kelas</p>
            </div>
            <div class="icon">
                <i class="fas fa-school"></i>
            </div>
        </div>
    </div>

    <!-- Hadir Hari Ini -->
    <div class="col-lg-3 col-12">
        <div class="small-box bg-success">
            <div class="inner">
                <h3>{{ $hadirHariIni }}</h3>
                <p>Siswa Hadir Hari Ini</p>
            </div>
            <div class="icon">
                <i class="fas fa-check-circle"></i>
            </div>
        </div>
    </div>

</div>

<div class="row mt-4">

    <!-- Grafik -->
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Statistik Siswa per Kelas</h3>
            </div>
            <div class="card-body">
                <canvas id="kelasChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Aktivitas -->
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Aktivitas Terbaru</h3>
            </div>
            <div class="card-body">
                <ul class="list-group">

                    @forelse($aktivitas ?? [] as $item)
                        <li class="list-group-item">{{ $item }}</li>
                    @empty
                        <li class="list-group-item">Belum ada aktivitas</li>
                    @endforelse

                </ul>
            </div>
        </div>
    </div>

</div>

<script>
const ctx = document.getElementById('kelasChart');

new Chart(ctx, {
    type: 'bar',
    data: {
        labels: {!! json_encode($labels ?? ['Kelas PG','Kelas A','Kelas B']) !!},
        datasets: [{
            label: 'Jumlah Siswa',
            data: {!! json_encode($data ?? [0,0,0]) !!},
            backgroundColor: [
                '#00BCD4',
                '#E91E63',
                '#FF9800'
            ]
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: {
                display: false
            }
        }
    }
});
</script>

@endsection