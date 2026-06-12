<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class Kriteria8DimensiSeeder extends Seeder
{
    public function run()
{
    // 1. Matikan proteksi foreign key
    \Schema::disableForeignKeyConstraints();

    // 2. Kosongkan tabel (Truncate)
    DB::table('kriterias')->truncate();

    // 3. Masukkan data
    $data = [
        [
            'kode' => 'C1',
            'nama_kriteria' => 'Keimanan & Ketakwaan',
            'bobot' => 0.15,
            'deskripsi' => 'Mengenal Tuhan, menyayangi ciptaan-Nya, dan mulai membiasakan perilaku berakhlak mulia.'
        ],
        [
            'kode' => 'C2',
            'nama_kriteria' => 'Kewargaan',
            'bobot' => 0.10,
            'deskripsi' => 'Menumbuhkan rasa cinta tanah air, menghargai perbedaan, dan menjaga lingkungan sekitar.'
        ],
        [
            'kode' => 'C3',
            'nama_kriteria' => 'Penalaran Kritis',
            'bobot' => 0.10,
            'deskripsi' => 'Kemampuan bertanya, memiliki rasa ingin tahu tinggi, dan memecahkan masalah sederhana.'
        ],
        [
            'kode' => 'C4',
            'nama_kriteria' => 'Kreativitas',
            'bobot' => 0.10,
            'deskripsi' => 'Kemampuan mengekspresikan ide melalui karya seni atau menemukan cara baru dalam bermain.'
        ],
        [
            'kode' => 'C5',
            'nama_kriteria' => 'Kolaborasi',
            'bobot' => 0.15,
            'deskripsi' => 'Kemampuan bekerja sama, berbagi mainan, dan peduli untuk membantu teman.'
        ],
        [
            'kode' => 'C6',
            'nama_kriteria' => 'Kemandirian',
            'bobot' => 0.15,
            'deskripsi' => 'Kemampuan mengurus diri sendiri dan rasa tanggung jawab terhadap tugas atau miliknya.'
        ],
        [
            'kode' => 'C7',
            'nama_kriteria' => 'Kesehatan',
            'bobot' => 0.15,
            'deskripsi' => 'Kesadaran menjaga kebersihan diri, makan sehat, dan aktif bergerak untuk kebugaran.'
        ],
        [
            'kode' => 'C8',
            'nama_kriteria' => 'Komunikasi',
            'bobot' => 0.10,
            'deskripsi' => 'Kemampuan menyampaikan perasaan secara lisan dan berinteraksi dengan sopan.'
        ],
    ];

    DB::table('kriterias')->insert($data);

    // 4. Hidupkan kembali proteksi foreign key
    \Schema::enableForeignKeyConstraints();
}

}
