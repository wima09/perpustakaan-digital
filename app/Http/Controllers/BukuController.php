<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use Illuminate\Http\Request;

class BukuController extends Controller
{
    public function index()
    {
        return view('buku.index', ['buku' => Buku::orderBy('judul')->paginate(10)]);
    }

    public function create()
    {
        return view('buku.form', ['buku' => new Buku(['stok' => 1])]);
    }

    public function store(Request $request)
    {
        Buku::create($this->validated($request));

        return redirect()->route('buku.index')->with('status', 'Buku ditambahkan.');
    }

    public function edit(Buku $buku)
    {
        return view('buku.form', compact('buku'));
    }

    public function update(Request $request, Buku $buku)
    {
        $buku->update($this->validated($request));

        return redirect()->route('buku.index')->with('status', 'Buku diperbarui.');
    }

    public function destroy(Buku $buku)
    {
        $buku->delete();

        return redirect()->route('buku.index')->with('status', 'Buku dihapus.');
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'judul'   => ['required', 'string', 'max:255'],
            'penulis' => ['required', 'string', 'max:255'],
            'tahun'   => ['nullable', 'integer', 'between:1000,' . date('Y')],
            'stok'    => ['required', 'integer', 'min:0'],
        ]);
    }
}