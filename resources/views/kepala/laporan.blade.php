@extends('layouts.adminlte')

@section('title', 'Laporan Penilaian Perkembangan Anak')

@section('content')
<div class="container-fluid">
    
    <!-- FILTER KELAS -->
    <div class="card shadow-sm mb-4" style="border-radius: 15px;">
        <div class="card-body">
            <form method="GET" action="{{ route('kepala.penilaian') }}" class="row align-items-end">
                <div class="col-md-4">
                    <label class="form-label font-weight-bold">Pilih Kelas untuk Monitoring</label>
                    <select name="kelas_id" class="form-control" onchange="this.form.submit()" style="border-radius: 10px;">
                        <option value="">-- Semua Kelas --</option>
                        @foreach($kelas as $k)
                            <option value="{{ $k->id }}" {{ $kelasId == $k->id ? 'selected' : '' }}>
                                {{ $k->nama_kelas }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <a href="{{ route('kepala.penilaian') }}" class="btn btn-secondary w-100" style="border-radius: 10px;">
                        <i class="fas fa-sync-alt mr-1"></i> Reset
                    </a>
                </div>
            </form>
        </div>
    </div>

    <div class="row">
        <!-- GRAFIK KESIMPULAN -->
        <div class="col-lg-12 mb-4">
            <div class="card shadow-sm border-0" style="border-radius: 15px;">
                <div class="card-header bg-white">
                    <h5 class="card-title font-weight-bold mb-0">
                        <i class="fas fa-chart-bar mr-2 text-info"></i>
                        Grafik Kesimpulan Penilaian {{ $kelasId ? 'Kelas ' . $kelas->find($kelasId)->nama_kelas : '(Semua Kelas)' }}
                    </h5>
                </div>
                <div class="card-body">
                    <div style="height: 350px;">
                        <canvas id="chartPenilaian"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- TABEL DATA -->
        <div class="col-lg-12">
            <div class="card shadow-sm border-0" style="border-radius: 15px;">
                <div class="card-header bg-info text-white" style="border-top-left-radius: 15px; border-top-right-radius: 15px;">
                    <h3 class="card-title"><i class="fas fa-star mr-2"></i> Rekap Penilaian Per Dimensi</h3>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover table-striped text-center" id="table-penilaian">
                            <thead class="bg-light">
                                <tr>
                                    <th rowspan="2" class="align-middle">No</th>
                                    <th rowspan="2" class="align-middle">Nama Siswa</th>
                                    <th rowspan="2" class="align-middle">Kelas</th>
                                    <th colspan="{{ count($kriterias) }}" class="align-middle">Dimensi / Kriteria</th>
                                </tr>
                                <tr>
                                    @foreach($kriterias as $k)
                                        <th title="{{ $k->nama_kriteria }}">{{ $k->kode }}</th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($tabelData as $i => $item)
                                <tr>
                                    <td>{{ $i + 1 }}</td>
                                    <td class="text-left"><strong>{{ $item['nama'] }}</strong></td>
                                    <td><span class="badge badge-light border">{{ $item['kelas'] }}</span></td>
                                    @foreach($kriterias as $k)
                                        @php $dataNilai = $item['nilai'][$k->id]; @endphp
                                        <td>
                                            @if($dataNilai)
                                                <div class="font-weight-bold {{ $dataNilai['angka'] >= 85 ? 'text-success' : ($dataNilai['angka'] >= 75 ? 'text-primary' : ($dataNilai['angka'] >= 60 ? 'text-warning' : 'text-danger')) }}">
                                                    {{ $dataNilai['angka'] }}
                                                </div>
                                                <small class="badge {{ $dataNilai['angka'] >= 85 ? 'badge-success' : ($dataNilai['angka'] >= 75 ? 'badge-primary' : ($dataNilai['angka'] >= 60 ? 'badge-warning' : 'badge-danger')) }}" style="font-size: 10px;">
                                                    {{ $dataNilai['skala'] }}
                                                </small>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                    @endforeach
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="{{ 3 + count($kriterias) }}" class="text-center py-5">
                                        <i class="fas fa-info-circle fa-3x text-muted mb-3"></i>
                                        <p class="text-muted">Belum ada data penilaian untuk filter ini.</p>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="mt-3">
                        <div class="row">
                            <div class="col-md-6">
                                <small class="text-muted">
                                    <strong>Keterangan Kode Dimensi:</strong>
                                    <div class="row mt-2">
                                        @foreach($kriterias as $k)
                                            <div class="col-md-6 mb-1">
                                                <span class="badge badge-secondary">{{ $k->kode }}</span> : <span style="font-size: 11px;">{{ $k->nama_kriteria }}</span>
                                            </div>
                                        @endforeach
                                    </div>
                                </small>
                            </div>
                            <div class="col-md-6">
                                <small class="text-muted">
                                    <strong>Keterangan Skala Nilai:</strong>
                                    <div class="row mt-2">
                                        <div class="col-6 mb-1"><span class="badge badge-success">SB</span> : <span style="font-size: 11px;">Sangat Baik</span></div>
                                        <div class="col-6 mb-1"><span class="badge badge-primary">BSH</span> : <span style="font-size: 11px;">Berkembang Sesuai Harapan</span></div>
                                        <div class="col-6 mb-1"><span class="badge badge-warning">MB</span> : <span style="font-size: 11px;">Mulai Berkembang</span></div>
                                        <div class="col-6 mb-1"><span class="badge badge-danger">BB</span> : <span style="font-size: 11px;">Belum Berkembang</span></div>
                                    </div>
                                </small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    $(document).ready(function() {
        const ctx = document.getElementById('chartPenilaian').getContext('2d');
        const chartLabels = @json($chartLabels);
        const chartData = @json($chartData);

        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: chartLabels,
                datasets: [{
                    label: 'Rata-rata Skor',
                    data: chartData,
                    backgroundColor: 'rgba(23, 162, 184, 0.7)',
                    borderColor: 'rgba(23, 162, 184, 1)',
                    borderWidth: 1,
                    borderRadius: 8
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        max: 100,
                        title: {
                            display: true,
                            text: 'Skor (0-100)'
                        }
                    },
                    x: {
                        ticks: {
                            callback: function(value, index) {
                                // Potong teks label jika terlalu panjang
                                let label = chartLabels[index];
                                return label.length > 20 ? label.substring(0, 17) + '...' : label;
                            }
                        }
                    }
                },
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return ' Rata-rata: ' + context.parsed.y;
                            }
                        }
                    }
                }
            }
        });
    });
</script>
@endpush
@endsection