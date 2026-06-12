@extends('layouts.adminlte')

@section('content')

<style>
.content{
    background:#f8f9fc;
    min-height:100vh;
    padding:20px 0;
}

.page-title{
    font-weight:800;
    color:#2c3e50;
    letter-spacing: -0.5px;
}

.custom-card{
    border:none;
    border-radius:20px;
    overflow:hidden;
    background:#fff;
    box-shadow:0 8px 30px rgba(0,0,0,.05);
}

.custom-header{
    background:#fff;
    border-bottom:1px solid #f0f0f0;
    padding:20px 25px;
}

.custom-header h5{
    margin:0;
    font-weight:800;
    color:#2c3e50;
    display: flex;
    align-items: center;
}

.form-select,
.form-control{
    border-radius:12px !important;
    height:50px;
    border:2px solid #edf2f7;
    background: #f8fafc;
    font-weight: 600;
    transition: all 0.3s ease;
}

.form-select:focus,
.form-control:focus{
    border-color:#667eea;
    background: #fff;
    box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.1) !important;
}

/* TABLE */
.table-container{
    border-radius:16px;
    overflow:hidden;
    border: 1px solid #edf2f7;
}

.table{
    margin-bottom:0;
}

.table thead{
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color:white;
}

.table thead th{
    vertical-align:middle;
    text-align:center;
    font-weight: 700;
    text-transform: uppercase;
    font-size:12px;
    letter-spacing: 0.5px;
    padding:18px 12px;
    border:none;
}

.table tbody td{
    vertical-align:middle;
    padding:15px 12px;
}

.table tbody tr:hover{
    background:#f8fafc;
}

.nama-siswa{
    font-weight:700;
    color:#1a202c;
    font-size:15px;
}

.nisn{
    font-size:12px;
    color:#718096;
    margin-top: 2px;
}

.nilai-select{
    min-width:140px;
    border-radius:10px !important;
    font-size:13px;
    height: 40px !important;
    border: 2px solid #e2e8f0 !important;
}

.legend-box{
    background: linear-gradient(to right, #ffffff, #f8faff);
    border:1px solid #e2e8f0;
    border-radius:20px;
    padding:25px;
}

.legend-title{
    font-weight:800;
    margin-bottom:20px;
    color:#2c3e50;
    font-size: 1.1rem;
}

.legend-item{
    margin-bottom:12px;
    font-size:13px;
    display: flex;
    align-items: center;
    color: #4a5568;
}

.legend-badge{
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color:white;
    min-width: 45px;
    height: 28px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius:8px;
    font-size:11px;
    font-weight: 800;
    margin-right:12px;
    box-shadow: 0 4px 6px rgba(118, 75, 162, 0.15);
}

.btn-custom{
    border-radius:14px;
    padding:14px 28px;
    font-weight:700;
    transition: all 0.3s ease;
    border: none;
}

.btn-save{
    background: linear-gradient(135deg, #00b09b 0%, #96c93d 100%);
    color:white;
    box-shadow: 0 4px 15px rgba(0, 176, 155, 0.2);
}

.btn-save:hover{
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(0, 176, 155, 0.3);
    color: white;
}

.btn-history{
    background: white;
    color: #4a5568;
    border: 2px solid #e2e8f0;
}

.btn-history:hover{
    background: #f8fafc;
    border-color: #cbd5e0;
}

.info-box{
    background: #ebf4ff;
    border:none;
    border-radius:16px;
    color:#2c5282;
    padding: 20px;
}

.loading-box{
    padding:50px;
    text-align:center;
}

@media(max-width:768px){
    .table thead th{
        padding:12px 8px;
    }
    .nilai-select{
        min-width:120px;
    }
}
</style>

<div class="container-fluid py-4">

    <!-- HEADER -->
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap">
        <div>
            <h2 class="page-title mb-1">
                Input Nilai Perkembangan
            </h2>
            <p class="text-muted mb-0">Kelola capaian perkembangan anak didik secara digital</p>
        </div>

        <a href="{{ route('guru.nilai.riwayat') }}"
           class="btn btn-history btn-custom shadow-sm mt-3 mt-md-0">
            <i class="fa-solid fa-square-poll-vertical mr-2"></i>
            Hasil Penilaian
        </a>
    </div>

    @if($myKelas)
        <div class="alert alert-info border-0 shadow-sm mb-4" style="border-radius: 15px;">
            <div class="d-flex align-items-center">
                <div class="mr-3 bg-white rounded-circle d-flex align-items-center justify-content-center" style="width: 45px; height: 45px;">
                    <i class="fas fa-user-tie text-info"></i>
                </div>
                <div>
                    <h6 class="mb-0 font-weight-bold">Halo, Anda adalah Guru Kelas <strong>{{ $myKelas->nama_kelas }}</strong></h6>
                    <small>Silakan input nilai untuk anak didik di kelas Anda.</small>
                </div>
            </div>
        </div>
    @endif

    <!-- CARD -->
    <div class="custom-card">
        <div class="custom-header">
            <h5>
                <i class="fas fa-filter mr-2 text-primary"></i>
                Filter Data
            </h5>
        </div>

        <div class="card-body p-4">
            <!-- PILIH KELAS, SEMESTER & TAHUN AJARAN -->
            <div class="row align-items-end">
                @if(!$isGuruKelas)
                <div class="col-lg-3 col-md-6 mb-3 mb-lg-0">
                    <label class="mb-2 font-weight-bold text-muted small text-uppercase">
                        Pilih Kelas
                    </label>
                    <select id="kelasSelect" class="form-select">
                        <option value="">-- Pilih Kelas --</option>
                        @foreach($kelas as $k)
                            <option value="{{ $k->id }}" {{ $kelas_id == $k->id ? 'selected' : '' }}>
                                {{ $k->nama_kelas }}
                            </option>
                        @endforeach
                    </select>
                </div>
                @endif
                <div class="col-lg-3 col-md-6 mb-3 mb-lg-0">
                    <label class="mb-2 font-weight-bold text-muted small text-uppercase">
                        Pilih Semester
                    </label>
                    <select id="semesterSelect" class="form-select">
                        <option value="1" {{ $semester == 1 ? 'selected' : '' }}>Semester 1</option>
                        <option value="2" {{ $semester == 2 ? 'selected' : '' }}>Semester 2</option>
                    </select>
                </div>
                <div class="col-lg-3 col-md-6 mb-3 mb-lg-0">
                    <label class="mb-2 font-weight-bold text-muted small text-uppercase">
                        Tahun Ajaran
                    </label>
                    <select id="tahunAjaranSelect" class="form-select">
                        @foreach($tahunAjaranOptions as $ta)
                            <option value="{{ $ta }}" {{ $tahunAjaran == $ta ? 'selected' : '' }}>{{ $ta }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-lg-3 text-right d-none d-lg-block">
                    <span class="badge badge-light p-3 rounded-pill text-muted">
                        <i class="fas fa-info-circle mr-1"></i>
                        Data disimpan otomatis saat tombol "Simpan Semua" ditekan
                    </span>
                </div>
            </div>

            <!-- WRAPPER -->
            <div id="siswaWrapper" style="display:{{ $kelas_id ? 'block' : 'none' }};" class="mt-5">

                <!-- LEGEND -->
                <div class="legend-box mb-5">
                    <div class="legend-title">
                        <i class="fas fa-brain mr-2 text-primary"></i>
                        Dimensi Penilaian
                    </div>
                    <div class="row">
                        @foreach($kriterias as $k)
                            <div class="col-lg-3 col-md-4 col-sm-6">
                                <div class="legend-item">
                                    <span class="legend-badge">{{ $k->kode }}</span>
                                    <span class="font-weight-bold small">{{ $k->nama_kriteria }}</span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- FORM -->
                <form method="POST" action="{{ route('guru.nilai.store') }}" id="formNilai">
                    @csrf
                    <input type="hidden" name="semester" id="semesterInput" value="{{ $semester }}">
                    <input type="hidden" name="tahun_ajaran" id="tahunAjaranInput" value="{{ $tahunAjaran }}">
                    <div class="table-container shadow-sm">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th width="250" class="text-left">Anak Didik</th>
                                        @foreach($kriterias as $k)
                                            <th title="{{ $k->nama_kriteria }}">{{ $k->kode }}</th>
                                        @endforeach
                                    </tr>
                                </thead>
                                <tbody id="bodyNilai">
                                    @if(!$kelas_id)
                                        <tr>
                                            <td colspan="20" class="loading-box">
                                                <div class="text-muted">
                                                    <i class="fas fa-arrow-up fa-2x mb-3 d-block opacity-25"></i>
                                                    Silakan pilih kelas terlebih dahulu
                                                </div>
                                            </td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- INFO -->
                    <div class="info-box mt-5">
                        <div class="d-flex">
                            <i class="fas fa-lightbulb fa-2x mr-3 opacity-50"></i>
                            <div>
                                <h6 class="font-weight-bold mb-1 text-uppercase small">Petunjuk Penilaian:</h6>
                                <p class="mb-0 small opacity-75">
                                    Pilih capaian perkembangan (BB, MB, BSH, BSB) sesuai dengan hasil pengamatan harian anak.
                                    Pastikan semua kolom terisi sebelum menekan tombol simpan.
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- BUTTON -->
                    <div class="text-right mt-5">
                        <button type="submit" class="btn btn-save btn-custom shadow">
                            <i class="fas fa-check-circle mr-2"></i>
                            Simpan Penilaian
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')

<script>
$(function() {
    const kelasSelect = $('#kelasSelect');
    const semesterSelect = $('#semesterSelect');
    const tahunAjaranSelect = $('#tahunAjaranSelect');
    const semesterInput = $('#semesterInput');
    const tahunAjaranInput = $('#tahunAjaranInput');
    const siswaWrapper = $('#siswaWrapper');
    const bodyNilai = $('#bodyNilai');
    const daftarSkala = @json($skalas);
    const kriteriaIds = @json($kriterias->pluck('id'));
    const isGuruKelas = @json($isGuruKelas);
    const kelasIdDefault = @json($kelas_id);
    let currentSemester = {{ $semester }};
    let currentTahunAjaran = '{{ $tahunAjaran }}';

    function loadSiswa(kelasId) {
        if(!kelasId) {
            siswaWrapper.fadeOut();
            return;
        }

        siswaWrapper.fadeIn();
        bodyNilai.html(`
            <tr>
                <td colspan="20" class="loading-box">
                    <div class="spinner-border text-primary mb-3"></div>
                    <div class="font-weight-bold">Memuat data anak didik...</div>
                </td>
            </tr>
        `);

        $.get("{{ route('guru.nilai.getSiswa') }}", { kelas_id: kelasId, semester: currentSemester, tahun_ajaran: currentTahunAjaran }, function(data) {
            bodyNilai.empty();

            if(data.length === 0) {
                bodyNilai.html(`
                    <tr>
                        <td colspan="20" class="text-center py-5">
                            <i class="fas fa-user-slash fa-3x mb-3 text-muted opacity-25"></i>
                            <p class="text-muted font-weight-bold">Tidak ada siswa di kelas ini</p>
                        </td>
                    </tr>
                `);
                return;
            }

            data.forEach(siswa => {
                let existingNilai = {};
                if (siswa.nilai_perkembangans) {
                    siswa.nilai_perkembangans.forEach(n => {
                        existingNilai[n.kriteria_id] = n.nilai;
                    });
                }

                let row = `
                    <tr>
                        <td class="text-left">
                            <div class="nama-siswa">${siswa.nama_siswa}</div>
                            <div class="nisn">NISN: ${siswa.nisn ?? '-'}</div>
                        </td>
                `;

                kriteriaIds.forEach(kId => {
                    let currentVal = existingNilai[kId] ?? '';
                    
                    row += `
                        <td class="text-center">
                            <select name="nilai[${siswa.id}][${kId}]" class="form-select form-select-sm nilai-select">
                                <option value="">- Pilih -</option>
                                ${daftarSkala.map(s => `
                                    <option value="${s.nilai}" ${currentVal == s.nilai ? 'selected' : ''}>
                                        ${s.keterangan}
                                    </option>
                                `).join('')}
                            </select>
                        </td>
                    `;
                });

                row += `</tr>`;
                bodyNilai.append(row);
            });
        }).fail(function() {
            bodyNilai.html(`
                <tr>
                    <td colspan="20" class="text-center py-5 text-danger">
                        <i class="fas fa-exclamation-circle fa-3x mb-3"></i>
                        <p class="font-weight-bold">Gagal memuat data siswa</p>
                    </td>
                </tr>
            `);
        });
    }

    semesterSelect.on('change', function() {
        currentSemester = $(this).val();
        semesterInput.val(currentSemester);
        if (kelasIdDefault || (kelasSelect.length && kelasSelect.val())) {
            loadSiswa(kelasIdDefault || kelasSelect.val());
        }
    });

    tahunAjaranSelect.on('change', function() {
        currentTahunAjaran = $(this).val();
        tahunAjaranInput.val(currentTahunAjaran);
        if (kelasIdDefault || (kelasSelect.length && kelasSelect.val())) {
            loadSiswa(kelasIdDefault || kelasSelect.val());
        }
    });

    if (kelasSelect.length) {
        kelasSelect.on('change', function() {
            loadSiswa($(this).val());
        });
    }

    if (kelasIdDefault) {
        loadSiswa(kelasIdDefault);
    } else if (isGuruKelas && kelasIdDefault) {
        loadSiswa(kelasIdDefault);
    }
});
</script>

@endpush