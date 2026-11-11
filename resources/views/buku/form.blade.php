<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ $buku->exists ? 'Ubah Buku' : 'Tambah Buku' }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-xl mx-auto sm:px-6 lg:px-8 bg-white shadow sm:rounded-lg p-6">
            <form method="POST"
                  action="{{ $buku->exists ? route('buku.update', $buku) : route('buku.store') }}"
                  class="space-y-4">
                @csrf
                @if ($buku->exists) @method('PUT') @endif

                @foreach ([['judul', 'Judul', 'text'], ['penulis', 'Penulis', 'text'], ['tahun', 'Tahun', 'number'], ['stok', 'Stok', 'number']] as [$name, $label, $type])
                    <div>
                        <x-input-label :for="$name" :value="$label" />
                        <x-text-input :id="$name" :name="$name" :type="$type"
                                      class="mt-1 block w-full" :value="old($name, $buku->$name)" />
                        <x-input-error :messages="$errors->get($name)" class="mt-2" />
                    </div>
                @endforeach

                <div class="flex items-center gap-3">
                    <x-primary-button>Simpan</x-primary-button>
                    <a href="{{ route('buku.index') }}" class="text-gray-600">Batal</a>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>