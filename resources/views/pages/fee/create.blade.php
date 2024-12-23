<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('SPP') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    <form method="post" action="{{ route('fee.store') }}" class="mt-6 space-y-6">
                        @csrf

                        <!-- Input Tahun -->
                        <div>
                            <x-input-label for="year" :value="__('Tahun')" />
                            <x-text-input id="year" name="year" type="number" min="2010" max="2030"
                                          class="mt-1 block w-full" :value="old('year')" 
                                          placeholder="Masukkan tahun, misalnya 2024" autofocus />
                            <x-input-error class="mt-2" :messages="$errors->get('year')" />
                        </div>

                        <!-- Input Nominal -->
                        <div>
                            <x-input-label for="nominal" :value="__('Nominal')" />
                            <x-text-input id="nominal" name="nominal" type="number" min="0"
                                          class="mt-1 block w-full" :value="old('nominal')" 
                                          placeholder="Masukkan nominal, misalnya 500000" />
                            <x-input-error class="mt-2" :messages="$errors->get('nominal')" />
                        </div>

                        <!-- Tombol Simpan -->
                        <div class="flex items-center gap-4">
                            <x-primary-button>{{ __('Simpan') }}</x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
