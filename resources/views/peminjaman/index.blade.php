<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Peminjaman</h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            @if (session('status'))
                <div class="mb-4 p-3 bg-green-100 text-green-800 rounded">{{ session('status') }}</div>
            @endif

            <div class="bg-white shadow sm:rounded-lg overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead class="bg-gray-50 text-left">
                        <tr>
                            <th class="p-3">ID</th>
                            <th class="p-3">Siswa</th>
                            <th class="p-3">Buku</th>
                            <th class="p-3">Tgl Pinjam</th>
                            <th class="p-3">Status</th>
                            <th class="p-3">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($peminjaman as $p)
                            <tr class="border-t">
                                <td class="p-3">{{ $p->id }}</td>
                                <td class="p-3">{{ $p->user->name }}</td>
                                <td class="p-3">{{ $p->buku->judul }}</td>
                                <td class="p-3">{{ $p->tanggal_pinjam->format('d-m-Y') }}</td>
                                <td class="p-3">{{ $p->status }}</td>
                                <td class="p-3 flex gap-3">
                                    <a href="{{ route('peminjaman.show', $p) }}" class="text-blue-600">Detail</a>
                                    @if ($p->status === 'dipinjam' && auth()->user()->hasRole('petugas', 'admin'))
                                        <form method="POST" action="{{ route('peminjaman.kembali', $p) }}">
                                            @csrf
                                            @method('PATCH')
                                            <button class="text-green-700">Kembalikan</button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="6" class="p-3 text-center text-gray-500">Belum ada peminjaman.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-4">{{ $peminjaman->links() }}</div>
        </div>
    </div>
</x-app-layout>