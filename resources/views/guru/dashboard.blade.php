@extends('layouts.adminlte')
@section('title', 'Dashboard Guru')

@section('content')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<div class="row">

    <!-- Presensi Guru -->
    <div class="col-lg-3 col-6">
        <div class="small-box bg-info">
            <div class="inner">
                <h3>{{ $presensiGuruHariIni ? '✔' : '✖' }}</h3>
                <p>Presensi Hari Ini</p>
            </div>
            <div class="icon">
                <i class="fas fa-user-check"></i>
            </div>
        </div>
    </div>

    <!-- Total Presensi Siswa -->
    <div class="col-lg-3 col-6">
        <div class="small-box bg-success">
            <div class="inner">
                <h3>{{ $presensiSiswaHariIni }}</h3>
                <p>Siswa Presensi Hari Ini</p>
            </div>
            <div class="icon">
                <i class="fas fa-users"></i>
            </div>
        </div>
    </div>

    <!-- Hadir -->
    <div class="col-lg-3 col-6">
        <div class="small-box bg-primary">
            <div class="inner">
                <h3>{{ $hadir }}</h3>
                <p>Hadir</p>
            </div>
            <div class="icon">
                <i class="fas fa-user-check"></i>
            </div>
        </div>
    </div>

    <!-- Alpha -->
    <div class="col-lg-3 col-6">
        <div class="small-box bg-danger">
            <div class="inner">
                <h3>{{ $alpha }}</h3>
                <p>Alpha</p>
            </div>
            <div class="icon">
                <i class="fas fa-user-times"></i>
            </div>
        </div>
    </div>

</div>

<div class="row mt-4">

    <!-- Grafik -->
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Grafik Presensi Siswa Hari Ini</h3>
            </div>
            <div class="card-body">
                <canvas id="presensiChart"></canvas>
            </div>
        </div>
    </div>

</div>

<script>
const ctx = document.getElementById('presensiChart');

new Chart(ctx, {
    type: 'bar',
    data: {
        labels: ['Hadir', 'Izin', 'Sakit', 'Alpha'],
        datasets: [{
            label: 'Jumlah Siswa',
            data: [{{ $hadir }}, {{ $izin }}, {{ $sakit }}, {{ $alpha }}],
            backgroundColor: [
                '#00BCD4',
                '#E91E63',
                '#FF9800',
                '#4CAF50'
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