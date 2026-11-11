<?php

namespace Database\Seeders;

use App\Models\Buku;
use App\Models\Peminjaman;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $password = env('SEED_PASSWORD');
        if (! $password) {
            throw new \RuntimeException('Isi SEED_PASSWORD di .env sebelum menjalankan db:seed.');
        }

        $akun = [
            ['Administrator',        'admin@perpus.test',   'admin',   null],
            ['Petugas Perpustakaan', 'petugas@perpus.test', 'petugas', null],
            ['Siswa Satu',           'siswa1@perpus.test',  'siswa',   '230441100087'],  // mahasiswa ganti dengan NIM
            ['Siswa Dua',            'siswa2@perpus.test',  'siswa',   '240441100089'],
        ];

        foreach ($akun as [$nama, $email, $role, $nim]) {
            User::firstOrNew(['email' => $email])->forceFill([
                'name'              => $nama,
                'role'              => $role,
                'nim'               => $nim,
                'password'          => $password,   // di-hash otomatis oleh cast 'hashed'
                'email_verified_at' => now(),
            ])->save();
        }

        $buku = [
            ['Laskar Pelangi',                 'Andrea Hirata',         2005, 5],
            ['Bumi Manusia',                   'Pramoedya Ananta Toer', 1980, 3],
            ['Filosofi Teras',                 'Henry Manampiring',     2018, 4],
            ['Pemrograman Web dengan Laravel', 'Tim Penulis',           2024, 2],
        ];

        foreach ($buku as [$judul, $penulis, $tahun, $stok]) {
            Buku::firstOrCreate(['judul' => $judul], compact('penulis', 'tahun', 'stok'));
        }

        if (Peminjaman::count() === 0) {
            $s1 = User::where('email', 'siswa1@perpus.test')->first();
            $s2 = User::where('email', 'siswa2@perpus.test')->first();
            $b  = Buku::orderBy('id')->get();

            Peminjaman::create(['user_id' => $s1->id, 'buku_id' => $b[0]->id,
                'tanggal_pinjam' => now()->subDays(3)->toDateString()]);   // /peminjaman/1 milik siswa 1
            Peminjaman::create(['user_id' => $s2->id, 'buku_id' => $b[1]->id,
                'tanggal_pinjam' => now()->subDay()->toDateString()]);     // /peminjaman/2 milik siswa 2
        }
    }
}