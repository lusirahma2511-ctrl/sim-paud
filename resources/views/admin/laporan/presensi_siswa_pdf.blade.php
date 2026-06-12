<!-- HEADER -->
<div class="page-header">

    <div>
        <h1 class="page-title">
            <i class="fas fa-file-alt mr-2"></i>
            Laporan Presensi Siswa
        </h1>
    </div>

    <div class="action-group">

        <!-- KEMBALI -->
        <a href="{{ route('admin.laporan.index') }}"
           class="btn btn-modern btn-secondary">

            <i class="fas fa-arrow-left mr-1"></i>
            Kembali

        </a>

        <!-- BUTTON MODAL -->
        <button type="button"
                class="btn btn-modern btn-print"
                data-toggle="modal"
                data-target="#aksiLaporanModal">

            <i class="fas fa-file-export mr-1"></i>
            Export Laporan

        </button>

    </div>

</div> <!-- PENUTUP page-header -->