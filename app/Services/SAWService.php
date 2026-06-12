<?php

namespace App\Services;

class SAWService
{
    public function hitung($data, $bobot, $tipe)
    {
        // Jika data kosong, langsung kembalikan array kosong
        if (empty($data)) return [];

        // 1. NORMALISASI
        $normalisasi = [];
        foreach ($data as $i => $item) {
            foreach ($item as $k => $nilai) {
                // Lewati jika kriteria tidak ada di tabel bobot/tipe
                if (!isset($tipe[$k])) continue;

                if ($tipe[$k] == 'benefit') {
                    $max = max(array_column($data, $k));
                    // Proteksi agar tidak pembagian dengan nol
                    $normalisasi[$i][$k] = ($max > 0) ? ($nilai / $max) : 0;
                } else {
                    $min = min(array_column($data, $k));
                    // Proteksi agar tidak pembagian dengan nol
                    $normalisasi[$i][$k] = ($nilai > 0) ? ($min / $nilai) : 0;
                }
            }
        }

        // 2. HITUNG NILAI AKHIR & PERANGKINGAN
        $hasil = [];
        foreach ($normalisasi as $i => $item) {
            $total = 0;
            foreach ($item as $k => $nilai) {
                if (isset($bobot[$k])) {
                    $total += $nilai * $bobot[$k];
                }
            }
            
            // Simpan skor akhir (dibulatkan 3 angka di belakang koma agar rapi)
            $hasil[$i] = round($total, 3);
        }

        // 3. URUTKAN DARI TERBESAR KE TERKECIL (Ranking)
        arsort($hasil);

        return $hasil;
    }
}
