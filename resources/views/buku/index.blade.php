<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Daftar Buku</h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            @if (session('status'))
                <div class="mb-4 p-3 bg-green-100 text-green-800 rounded">{{ session('status') }}</div>
            @endif

            @if (auth()->user()->hasRole('admin'))
                <a href="{{ route('buku.create') }}" class="inline-block mb-4 px-4 py-2 bg-gray-800 text-white rounded">Tambah Buku</a>
            @endif

            <div class="bg-white shadow sm:rounded-lg overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead class="bg-gray-50 text-left">
                        <tr>
                            <th class="p-3">Judul</th>
                            <th class="p-3">Penulis</th>
                            <th class="p-3">Tahun</th>
                            <th class="p-3">Stok</th>
                            <th class="p-3">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($buku as $b)
                            <tr class="border-t">
                                <td class="p-3">{{ $b->judul }}</td>
                                <td class="p-3">{{ $b->penulis }}</td>
                                <td class="p-3">{{ $b->tahun }}</td>
                                <td class="p-3">{{ $b->stok }}</td>
                                <td class="p-3 flex gap-3">
                                    @if (auth()->user()->hasRole('admin'))
                                        <a href="{{ route('buku.edit', $b) }}" class="text-blue-600">Ubah</a>
                                        <form method="POST" action="{{ route('buku.destroy', $b) }}"
                                              onsubmit="return confirm('Hapus buku ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button class="text-red-600">Hapus</button>
                                        </form>
                                    @endif

                                    @if (auth()->user()->hasRole('siswa') && $b->stok > 0)
                                        <form method="POST" action="{{ route('peminjaman.store') }}">
                                            @csrf
                                            <input type="hidden" name="buku_id" value="{{ $b->id }}">
                                            <button class="text-green-700">Pinjam</button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="p-3 text-center text-gray-500">Belum ada buku.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-4">{{ $buku->links() }}</div>
        </div>
    </div>
</x-app-layout>