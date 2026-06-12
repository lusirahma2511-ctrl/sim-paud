<?php
namespace App\Imports;

use App\Models\Siswa;
use App\Models\OrangTua;
use App\Models\User;
use App\Models\Kelas;
use Maatwebsite\Excel\Concerns\ToModel;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class SiswaImport implements ToModel
{
    public function model(array $row)
    {
        // 🔥 FILTER PENGAMAN: 
        if (!isset($row[1]) || empty($row[1]) || $row[1] == 'Nama' || (isset($row[6]) && $row[6] == 'Tanggal Lahir')) {
            return null;
        }

        // 1. Logika pengolahan tanggal (Index 6)
        $tgl_lahir_raw = $row[6];
        $tanggal_lahir = null;
        $password_fix = '123456';

        if ($tgl_lahir_raw) {
            try {
                // Jika formatnya angka dari Excel
                if (is_numeric($tgl_lahir_raw)) {
                    $tanggal_lahir = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($tgl_lahir_raw)->format('Y-m-d');
                    $password_fix = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($tgl_lahir_raw)->format('dmY');
                } else {
                    // Jika formatnya string
                    $carbonDate = Carbon::parse($tgl_lahir_raw);
                    $tanggal_lahir = $carbonDate->format('Y-m-d');
                    $password_fix = $carbonDate->format('dmY');
                }
            } catch (\Exception $e) {
                $tanggal_lahir = null;
                $password_fix = '123456';
            }
        }

        // 2. Cari/Buat Kelas (Index 42)
        $namaKelasExcel = $row[42] ?? 'Tanpa Kelas';
        $kelas = Kelas::firstOrCreate(['nama_kelas' => $namaKelasExcel]);

        // 3. Buat/Update User Orang Tua (Username pakai NISN di Index 4)
        $nisn = $row[4] ?? 'TMP' . rand(1000, 9999);
        $user = User::updateOrCreate(
            ['username' => $nisn],
            [
                'name'     => $row[1],
                'password' => Hash::make($password_fix),
                'role'     => 'orang_tua',
                'status'   => 'Aktif',
            ]
        );

        // 4. Olah Nama & Alamat
        $namaLengkap = $row[1];
        $namaPanggilan = explode(' ', trim($namaLengkap))[0];
        
        // Alamat (Index 9=Alamat, 10=RT, 11=RW, 13=Desa, 14=Kec, 15=Kode Pos)
        $alamatLengkap = ($row[9] ?? '') . " RT " . ($row[10] ?? '0') . " RW " . ($row[11] ?? '0') . 
                         ", " . ($row[13] ?? '-') . ", " . ($row[14] ?? '-') . 
                         ", Kode Pos: " . ($row[15] ?? '-');

        // Jenis Kelamin (Index 3)
        $jk_mentah = strtolower($row[3] ?? '');
        $jk = ($jk_mentah == 'p' || $jk_mentah == 'perempuan') ? 'P' : 'L';

        // 5. Simpan/Update Data Orang Tua
        $ortu = OrangTua::updateOrCreate(
            ['user_id' => $user->id],
            [
                'nama_ayah'      => $row[24] ?? '-', 
                'nama_ibu'       => $row[30] ?? '-', 
                'pekerjaan_ayah' => $row[27] ?? '-', 
                'pekerjaan_ibu'  => $row[33] ?? '-', 
                'no_hp'          => $row[18] ?? '-',
                'alamat'         => $alamatLengkap,
            ]
        );

        // 6. Simpan/Update Data Siswa (Gunakan updateOrCreate untuk menghindari error duplicate NISN)
        return Siswa::updateOrCreate(
            ['nisn' => $nisn],
            [
                'orang_tua_id'   => $ortu->id,
                'kelas_id'       => $kelas->id, 
                'nik'            => $row[7] ?? null,
                'nama_siswa'     => $row[1],
                'nama_panggilan' => $namaPanggilan,
                'jk'             => $jk,
                'tempat_lahir'   => $row[5] ?? '-',
                'tanggal_lahir'  => $tanggal_lahir,
                'agama'          => $row[8] ?? 'Islam',
                'anak_ke'        => $row[57] ?? 1,
                'jumlah_saudara' => $row[64] ?? 0,
                'alamat'         => $alamatLengkap,
                'status'         => 'Aktif',
                'foto'           => 'default.png',
                'password'       => Hash::make($password_fix),
                'barcode'        => $nisn,
            ]
        );
    }
}
