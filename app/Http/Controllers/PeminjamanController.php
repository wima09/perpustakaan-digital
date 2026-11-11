<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use App\Models\Peminjaman;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PeminjamanController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        $query = Peminjaman::with(['user', 'buku'])->latest();

        if ($user->hasRole('siswa')) {
            $query->where('user_id', $user->id);   // siswa hanya melihat miliknya
        }

        return view('peminjaman.index', ['peminjaman' => $query->paginate(10)]);
    }

    public function show(Request $request, Peminjaman $peminjaman)
    {
        abort_if(
            $request->user()->hasRole('siswa') && $peminjaman->user_id !== $request->user()->id,
            403
        );

        $peminjaman->load(['user', 'buku']);

        return view('peminjaman.show', compact('peminjaman'));
    }

    public function store(Request $request)
    {
        $data = $request->validate(['buku_id' => ['required', 'exists:buku,id']]);

        DB::transaction(function () use ($data, $request) {
            $buku = Buku::lockForUpdate()->findOrFail($data['buku_id']);
            abort_if($buku->stok < 1, 422, 'Stok buku habis.');

            $buku->decrement('stok');

            Peminjaman::create([
                'user_id'        => $request->user()->id,
                'buku_id'        => $buku->id,
                'tanggal_pinjam' => now()->toDateString(),
            ]);
        });

        return redirect()->route('peminjaman.index')->with('status', 'Buku berhasil dipinjam.');
    }

    public function kembali(Peminjaman $peminjaman)
    {
        abort_if($peminjaman->status === 'dikembalikan', 422, 'Sudah dikembalikan.');

        DB::transaction(function () use ($peminjaman) {
            $peminjaman->update([
                'status'          => 'dikembalikan',
                'tanggal_kembali' => now()->toDateString(),
            ]);
            $peminjaman->buku()->increment('stok');
        });

        return back()->with('status', 'Buku dikembalikan.');
    }
}