@extends('layouts.adminlte')

@section('title', 'Rapor Anak')

@section('content')
<style>
    .content-wrapper {
        background: #f4f7fb !important;
    }

    .card {
        border-radius: 22px;
        border: none;
        box-shadow: 0 4px 18px rgba(0,0,0,0.05);
    }

    .card-header {
        background: linear-gradient(135deg, #4e73df, #6f42c1);
        color: white;
        border-top-left-radius: 22px !important;
        border-top-right-radius: 22px !important;
        padding: 20px;
    }

    .nilai-box {
        text-align: center;
        padding: 25px;
        border-radius: 15px;
        background: linear-gradient(135deg, #6f42c1, #a25ddc);
        color: white;
    }

    .nilai-besar {
        font-size: 3rem;
        font-weight: bold;
    }

    .badge-custom {
        padding: 6px 12px;
        border-radius: 30px;
        font-weight: 600;
    }

    .btn-modern {
        border-radius: 30px;
        padding: 10px 20px;
        font-weight: 600;
        transition: 0.2s;
    }

    .btn-modern:hover {
        transform: translateY(-2px);
    }
</style>

<div class="container-fluid">
    <!-- HEADER BUTTON -->
    <div class="d-flex justify-content-between align-items-center mb-4 pt-3">
        <a href="{{ route('orangtua.rapor') }}" class="btn btn-secondary btn-modern">
            <i class="fas fa-arrow-left mr-1"></i> Kembali
        </a>
        
        <div>
            <a href="{{ route('admin.erapor.download', $siswa->id) }}" class="btn btn-danger btn-modern">
                <i class="fas fa-file-pdf mr-1"></i> Unduh E-Rapor
            </a>
        </div>
    </div>

    <div class="row mb-4">
        <!-- DATA SISWA -->
        <div class="col-md-12">
            <div class="card h-100">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-user mr-2"></i> Data Peserta Didik</h5>
                </div>

                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr><th width="40%">NISN</th><td>: {{ $siswa->nisn }}</td></tr>
                                <tr><th>Nama</th><td>: <strong>{{ $siswa->nama_siswa }}</strong></td></tr>
                                <tr><th>Kelas</th><td>: {{ $siswa->kelas->nama_kelas ?? '-' }}</td></tr>
                            </table>
                        </div>

                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr><th width="40%">Jenis Kelamin</th><td>: {{ $siswa->jk == 'L' ? 'Laki-laki' : 'Perempuan' }}</td></tr>
                                <tr><th>Tanggal Lahir</th><td>: {{ \Carbon\Carbon::parse($siswa->tanggal_lahir)->translatedFormat('d F Y') }}</td></tr>
                                <tr><th>Status</th><td>: <span class="badge {{ ($siswa->status ?? 'Aktif') == 'Aktif' ? 'badge-success' : 'badge-danger' }}">{{ $siswa->status ?? 'Aktif' }}</span></td></tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- GRAFIK PERKEMBANGAN -->
    <div class="card mb-4">
        <div class="card-header">
            <h5 class="mb-0"><i class="fas fa-chart-bar mr-2"></i> Grafik Perkembangan Anak</h5>
        </div>
        <div class="card-body">
            <canvas id="perkembanganChart" style="max-height: 400px;"></canvas>
        </div>
    </div>

    <!-- TABEL NILAI -->
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0"><i class="fas fa-clipboard-list mr-2"></i> Detail Nilai Perkembangan</h5>
        </div>

        <div class="card-body table-responsive">
            <table class="table table-bordered text-center align-middle">
                <thead style="background:#f8fbff;">
                    <tr>
                        <th width="5%">No</th>
                        <th class="text-left">Dimensi Profil Lulusan</th>
                        <th width="25%">Capaian Perkembangan</th>
                    </tr>
                </thead>

                <tbody>
                @foreach($kriterias as $i => $k)
                    @php
                        $dataNilai = $nilaiArray[$k->id] ?? null;
                        $teksKualitatif = $dataNilai['teks'] ?? 'Belum Dinilai';

                        $color = 'secondary';
                        $skorAngka = $dataNilai['angka'] ?? 0;
                        if($skorAngka >= 85) $color = 'success';
                        elseif($skorAngka >= 75) $color = 'primary';
                        elseif($skorAngka >= 60) $color = 'warning';
                        elseif($skorAngka > 0) $color = 'danger';
                    @endphp

                    <tr>
                        <td>{{ $i+1 }}</td>
                        <td class="text-left"><strong>{{ $k->nama_kriteria }}</strong></td>
                        <td>
                            <span class="badge badge-{{ $color }} badge-custom">
                                {{ $teksKualitatif }}
                            </span>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const ctx = document.getElementById('perkembanganChart').getContext('2d');
            
            const kodeKriteria = @json($kriterias->pluck('kode'));
            const namaKriteria = @json($kriterias->pluck('nama_kriteria'));
            const skorAngka = @json(array_values(array_map(function($k) use ($nilaiArray) {
                return $nilaiArray[$k->id]['angka'] ?? 0;
            }, $kriterias->all())));
            
            const teksKualitatif = @json(array_values(array_map(function($k) use ($nilaiArray) {
                return $nilaiArray[$k->id]['teks'] ?? 'Belum Dinilai';
            }, $kriterias->all())));
            
            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: kodeKriteria,
                    datasets: [{
                        label: 'Perkembangan',
                        data: skorAngka,
                        backgroundColor: [
                            '#1cc88a',
                            '#36b9cc',
                            '#4e73df',
                            '#6f42c1',
                            '#e74a3b',
                            '#f6c23e'
                        ],
                        borderWidth: 0,
                        borderRadius: 8
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            callbacks: {
                                title: function(context) {
                                    return namaKriteria[context[0].dataIndex];
                                },
                                label: function(context) {
                                    return teksKualitatif[context.dataIndex];
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            max: 100,
                            ticks: {
                                display: false
                            }
                        }
                    }
                }
            });
        });
    </script>
</div>
@endsection
