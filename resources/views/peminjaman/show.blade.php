<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Detail Peminjaman #{{ $peminjaman->id }}</h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-xl mx-auto sm:px-6 lg:px-8 bg-white shadow sm:rounded-lg p-6 space-y-2">
            <p><strong>Siswa:</strong> {{ $peminjaman->user->name }} ({{ $peminjaman->user->nim }})</p>
            <p><strong>Buku:</strong> {{ $peminjaman->buku->judul }}</p>
            <p><strong>Tanggal pinjam:</strong> {{ $peminjaman->tanggal_pinjam->format('d-m-Y') }}</p>
            <p><strong>Tanggal kembali:</strong> {{ $peminjaman->tanggal_kembali?->format('d-m-Y') ?? '-' }}</p>
            <p><strong>Status:</strong> {{ $peminjaman->status }}</p>
            <a href="{{ route('peminjaman.index') }}" class="inline-block mt-4 text-blue-600">Kembali</a>
        </div>
    </div>
</x-app-layout>