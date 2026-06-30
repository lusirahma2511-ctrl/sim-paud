@extends('layouts.adminlte')

@section('content')

<style>
.content{
    background:#f4f6f9;
    min-height:100vh;
    padding:20px 0;
}

.scan-card{
    border:none;
    border-radius:20px;
    overflow:hidden;
    box-shadow:0 8px 30px rgba(0,0,0,.08);
    background:#fff;
    transition: transform 0.3s ease;
}

.scan-header{
    padding:20px 24px;
    border-bottom:1px solid #f0f0f0;
    font-weight:700;
    font-size:1.1rem;
    color:#2c3e50;
    background: #fff;
}

.scan-body{
    padding:25px;
}

.scan-icon{
    width:90px;
    height:90px;
    border-radius:50%;
    background:linear-gradient(135deg,#667eea 0%, #764ba2 100%);
    color:white;
    display:flex;
    align-items:center;
    justify-content:center;
    margin:0 auto 20px;
    font-size:40px;
    box-shadow:0 10px 20px rgba(118, 75, 162, 0.2);
}

.scan-title{
    font-size:24px;
    font-weight:800;
    margin-top:10px;
    color:#2c3e50;
    letter-spacing: -0.5px;
}

.scan-subtitle{
    color:#888;
    margin-bottom:25px;
    font-size: 0.95rem;
}

.barcode-input{
    height:60px;
    border-radius:12px !important;
    border:2px solid #e2e8f0;
    font-size:22px;
    text-align:center;
    font-weight:700;
    letter-spacing:2px;
    transition: all 0.3s ease;
}

.barcode-input:focus{
    border-color:#667eea;
    box-shadow:0 0 0 4px rgba(102, 126, 234, 0.1);
}

.btn-mode{
    border-radius:12px;
    padding:12px 24px;
    font-weight:700;
    transition: all 0.3s ease;
    flex: 1;
}

.btn-active{
    background:linear-gradient(135deg,#667eea 0%, #764ba2 100%) !important;
    border: none !important;
    color:white !important;
    box-shadow: 0 4px 12px rgba(118, 75, 162, 0.3);
}

.result-box{
    min-height:200px;
    display: flex;
    flex-direction: column;
    justify-content: center;
}

.result-item{
    border-radius:16px;
    border-left:5px solid #1cc88a;
    box-shadow:0 4px 12px rgba(0,0,0,.05);
}

.stats-box{
    border-radius:20px;
    background:white;
    padding:25px;
    text-align:center;
    box-shadow:0 8px 30px rgba(0,0,0,.08);
    height: 100%;
}

.stats-number{
    font-size:50px;
    font-weight:800;
    background: linear-gradient(135deg,#667eea 0%, #764ba2 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
}

.nav-pills .nav-link{
    border-radius:12px;
    padding:10px 20px;
    font-weight:600;
    color: #64748b;
    transition: all 0.3s ease;
}

.nav-pills .nav-link.active{
    background: #667eea;
    box-shadow: 0 4px 12px rgba(102, 126, 234, 0.2);
}

.loading-scan{
    display:none;
}

#reader video{
    border-radius:20px;
}

#reader{
    background:#fff;
    padding:10px;
}

.input-group .btn{
    border-radius:0 16px 16px 0 !important;
}

.scan-line{
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 4px;
    background: #667eea;
    opacity: 0.7;
    box-shadow: 0 0 15px 5px rgba(102, 126, 234, 0.5);
    z-index: 10;
    animation: scan 2s linear infinite;
    display: none;
}

@keyframes scan {
    0% { top: 0; }
    100% { top: 100%; }
}
</style>

<div class="container-fluid py-3">

    <ul class="nav nav-pills mb-4 justify-content-center">

        <li class="nav-item">
            <a href="#" class="nav-link active">
                <i class="fas fa-qrcode mr-2"></i>
                Scan Barcode
            </a>
        </li>

        <li class="nav-item">
            <a href="{{ route('guru.presensi.riwayat') }}"
               class="nav-link">
                <i class="fas fa-history mr-2"></i>
                Riwayat
            </a>
        </li>

    </ul>

    <div class="row">

        <!-- LEFT -->
        <div class="col-lg-7">

            <div class="scan-card mb-4">

                <div class="scan-header d-flex align-items-center justify-content-between">
                    <span>
                        <i class="fas fa-camera mr-2 text-primary"></i>
                        Scanner Presensi
                    </span>
                    <span class="badge badge-light px-3 py-2" id="modeBadge">
                        Mode: Siswa
                    </span>
                </div>

                <div class="scan-body text-center">

                    <!-- MODE TOGGLE -->
                    <div class="d-flex justify-content-center mb-4 p-1 bg-light rounded-pill" style="max-width: 300px; margin: 0 auto;">

                        <button id="scanSiswaBtn"
                                class="btn btn-mode btn-active">
                            Siswa
                        </button>

                        <button id="scanGuruBtn"
                                class="btn btn-mode">
                            Guru
                        </button>

                    </div>

                    <!-- CAMERA -->
                    <div id="reader-container" class="position-relative mb-4">
                        <div id="reader"
                             style="
                                width:100%;
                                max-width:400px;
                                margin:auto;
                                border-radius:20px;
                                overflow:hidden;
                                border:4px solid #f8f9fa;
                                box-shadow: 0 10px 30px rgba(0,0,0,0.1);
                             ">
                        </div>
                        <div class="scan-line" id="scanLine" style="display: none;"></div>
                    </div>

                    <div class="my-4">
                        <div class="d-flex align-items-center justify-content-center">
                            <hr class="flex-grow-1">
                            <span class="px-3 text-muted font-weight-bold small">ATAU INPUT MANUAL</span>
                            <hr class="flex-grow-1">
                        </div>
                    </div>

                    <!-- INPUT MANUAL -->
                    <div class="input-group input-group-lg shadow-sm rounded-pill overflow-hidden">

                        <input type="text"
                               id="barcodeInput"
                               class="form-control barcode-input border-0 bg-light"
                               placeholder="Masukkan Barcode / NISN"
                               autocomplete="off">

                        <div class="input-group-append">
                            <button type="button"
                                    class="btn btn-primary px-4 border-0"
                                    id="submitBarcode"
                                    style="background: #667eea;">
                                <i class="fas fa-arrow-right"></i>
                            </button>
                        </div>

                    </div>

                    <div class="loading-scan mt-4" id="loadingScan">
                        <div class="spinner-grow text-primary" role="status">
                            <span class="sr-only">Loading...</span>
                        </div>
                        <div class="mt-2 font-weight-bold text-primary">
                            Memproses data...
                        </div>
                    </div>

                </div>

            </div>

            <div class="scan-card">

                <div class="scan-header">
                    Presensi Hari Ini
                </div>

                <div class="scan-body">

                    @if($presensiGuruHariIni)

                        <div class="alert alert-success">

                            <h5 class="mb-2">
                                Presensi Berhasil
                            </h5>

                            Jam masuk:
                            <b>{{ $presensiGuruHariIni->jam_masuk }}</b>

                        </div>

                    @else

                        <div class="alert alert-warning mb-0">
                            Belum presensi hari ini
                        </div>

                    @endif

                </div>

            </div>

        </div>

        <!-- RIGHT -->
        <div class="col-lg-5">

            <div class="scan-card mb-4">

                <div class="scan-header">
                    <i class="fas fa-info-circle mr-2 text-info"></i>
                    Hasil Scan Terakhir
                </div>

                <div class="scan-body result-box">

                    <div id="scanResult">

                        <div class="text-center text-muted py-5">

                            <div class="mb-4">
                                <i class="fas fa-qrcode fa-4x opacity-25" style="color: #e2e8f0;"></i>
                            </div>

                            <p class="mb-0 font-weight-bold">
                                Belum ada scan
                            </p>
                            <small>
                                Hasil scan akan tampil otomatis di sini
                            </small>

                        </div>

                    </div>

                </div>

            </div>

            <div class="row">
                <div class="col-sm-6 mb-4">
                    <div class="stats-box">
                        <div class="stats-number">
                            {{ $presensiSiswaHariIni }}
                        </div>
                        <div class="text-muted font-weight-bold">
                            Siswa Hadir
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 mb-4">
                    <div class="stats-box d-flex flex-column justify-content-center">
                        <div class="text-muted mb-2 font-weight-bold">Status Anda:</div>
                        @if($presensiGuruHariIni)
                            <div class="badge badge-success p-3 rounded-pill">
                                <i class="fas fa-check-circle mr-1"></i>
                                Sudah Masuk
                                <br>
                                <small>{{ $presensiGuruHariIni->jam_masuk }}</small>
                            </div>
                        @else
                            <div class="badge badge-warning p-3 rounded-pill">
                                <i class="fas fa-clock mr-1"></i>
                                Belum Absen
                            </div>
                        @endif
                    </div>
                </div>
            </div>

        </div>

    </div>

</div>

@endsection

@push('scripts')

<!-- HTML5 QR CODE -->
<script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>

<script>
$(function () {

    let mode = 'siswa';
    let processing = false;
    let html5QrCode = null;
    let scannerActive = true; // Flag untuk menandakan apakah scanner aktif

    // =========================
    // ELEMENT
    // =========================
    const input      = $('#barcodeInput');
    const result     = $('#scanResult');
    const loading    = $('#loadingScan');
    const scanLine   = $('#scanLine');
    const modeBadge  = $('#modeBadge');

    // =========================
    // AUTO FOCUS
    // =========================
    function focusInput() {
        setTimeout(() => {
            input.focus();
        }, 300);
    }

    focusInput();

    // =========================
    // MODE BUTTON
    // =========================
    $('#scanSiswaBtn').click(function(){
        mode = 'siswa';
        $('.btn-mode').removeClass('btn-active');
        $(this).addClass('btn-active');
        modeBadge.text('Mode: Siswa').removeClass('badge-info').addClass('badge-light');
        input.attr('placeholder', 'Masukkan Barcode / NISN');
        focusInput();
    });

    $('#scanGuruBtn').click(function(){
        mode = 'guru';
        $('.btn-mode').removeClass('btn-active');
        $(this).addClass('btn-active');
        modeBadge.text('Mode: Guru').removeClass('badge-light').addClass('badge-info');
        input.attr('placeholder', 'Masukkan Barcode Guru');
        focusInput();
    });

    // =========================
    // RESULT SUCCESS
    // =========================
    function showSuccess(data) {
        result.hide().html(`
            <div class="alert alert-success border-0 shadow-sm p-3 mb-0" style="border-radius: 15px;">
                <div class="d-flex align-items-center">
                    <div class="mr-3 bg-white rounded-circle d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                        <i class="fas fa-check fa-2x text-success"></i>
                    </div>
                    <div>
                        <h5 class="mb-0 font-weight-bold text-dark">
                            ${data.nama ?? '-'}
                        </h5>
                        <div class="text-muted small">
                            ${data.kelas ?? 'Guru'}
                        </div>
                        <div class="badge badge-success mt-1">
                            <i class="fas fa-clock mr-1"></i> ${data.jam ?? '-'}
                        </div>
                    </div>
                </div>
            </div>
        `).fadeIn();
    }

    // =========================
    // RESULT ERROR
    // =========================
    function showError(message) {
        result.hide().html(`
            <div class="alert alert-danger border-0 shadow-sm p-3 mb-0" style="border-radius: 15px;">
                <div class="d-flex align-items-center">
                    <div class="mr-3 bg-white rounded-circle d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                        <i class="fas fa-times text-danger fa-lg"></i>
                    </div>
                    <div>
                        <div class="font-weight-bold text-dark">Gagal!</div>
                        <div class="small">${message}</div>
                    </div>
                </div>
            </div>
        `).fadeIn();
    }

    // =========================
    // PROCESS BARCODE
    // =========================
    function processBarcode(barcode) {
        barcode = barcode.trim();
        if (!barcode) return;
        if (processing) return;
        if (!scannerActive) return; // Cek apakah scanner aktif
        
        processing = true;
        scannerActive = false; // Nonaktifkan scanner
        
        loading.css('display', 'block');
        input.prop('disabled', true);
        input.val('');
        scanLine.hide();

        let url = mode === 'guru'
            ? "{{ route('guru.presensi.scanGuru') }}"
            : "{{ route('guru.presensi.scanSiswa') }}";

        $.ajax({
            url: url,
            type: 'POST',
            data: {
                _token: "{{ csrf_token() }}",
                barcode: barcode
            },
            success: function(res) {
                loading.hide();
                input.prop('disabled', false);
                
                if (res.success) {
                    showSuccess(res.data);
                    // Play sound if possible
                    try {
                        let audio = new Audio('https://assets.mixkit.co/active_storage/sfx/2354/2354-preview.mp3');
                        audio.play();
                    } catch(e) {}
                } else {
                    showError(res.message);
                }
                
                // Aktifkan scanner kembali setelah 3 detik
                setTimeout(() => {
                    processing = false;
                    scannerActive = true;
                    scanLine.show();
                    focusInput();
                }, 3000);
            },
            error: function(xhr) {
                console.log(xhr.responseText);
                loading.hide();
                input.prop('disabled', false);
                showError('Barcode gagal diproses. Periksa koneksi atau sistem.');
                
                // Aktifkan scanner kembali setelah 2 detik
                setTimeout(() => {
                    processing = false;
                    scannerActive = true;
                    scanLine.show();
                    focusInput();
                }, 2000);
            }
        });
    }

    // =========================
    // SUBMIT MANUAL
    // =========================
    function submitBarcode() {
        let barcode = input.val().trim();
        if (barcode === '') {
            input.addClass('is-invalid');
            setTimeout(() => input.removeClass('is-invalid'), 1000);
            return;
        }
        processBarcode(barcode);
    }

    // =========================
    // BUTTON SUBMIT
    // =========================
    $('#submitBarcode').click(function(e){
        e.preventDefault();
        submitBarcode();
    });

    // =========================
    // ENTER KEY
    // =========================
    input.keypress(function(e){
        if(e.which === 13) {
            e.preventDefault();
            submitBarcode();
        }
    });

    // =========================
    // CAMERA SCANNER
    // =========================
    function startScanner() {
        if (typeof Html5Qrcode === 'undefined') {
            $('#reader').html(`
                <div class="alert alert-warning border-0 shadow-sm">
                    <i class="fas fa-exclamation-triangle mr-1"></i>
                    Library scanner gagal dimuat
                </div>
            `);
            return;
        }

        Html5Qrcode.getCameras()
        .then(devices => {
            if (devices.length > 0) {
                html5QrCode = new Html5Qrcode("reader");
                
                // Try to find back camera
                let cameraId = devices[0].id;
                for (const device of devices) {
                    if (device.label.toLowerCase().includes('back') || device.label.toLowerCase().includes('rear')) {
                        cameraId = device.id;
                        break;
                    }
                }

                html5QrCode.start(
                    cameraId,
                    {
                        fps: 5, // Kurangi FPS menjadi 5 agar lebih lambat
                        qrbox: { width: 250, height: 250 },
                        aspectRatio: 1.0
                    },
                    function(decodedText) {
                        if (scannerActive && !processing) { // Hanya proses jika scanner aktif dan tidak sedang memproses
                            processBarcode(decodedText);
                        }
                    }
                ).then(() => {
                    scanLine.show();
                });
            } else {
                $('#reader').html(`
                    <div class="alert alert-warning border-0 shadow-sm py-4">
                        <i class="fas fa-video-slash fa-2x mb-2 d-block"></i>
                        Kamera tidak ditemukan
                    </div>
                `);
            }
        })
        .catch(err => {
            console.log(err);
            $('#reader').html(`
                <div class="alert alert-light border shadow-sm py-4">
                    <i class="fas fa-hand-pointer fa-2x mb-2 text-primary d-block"></i>
                    Kamera tidak tersedia.<br>
                    <span class="small text-muted">Gunakan input manual di bawah ini.</span>
                </div>
            `);
        });
    }

    startScanner();
});
</script>

<style>
.is-invalid {
    border: 2px solid #e3342f !important;
    animation: shake 0.5s;
}

@keyframes shake {
    0%, 100% { transform: translateX(0); }
    25% { transform: translateX(-5px); }
    75% { transform: translateX(5px); }
}
</style>

@endpush