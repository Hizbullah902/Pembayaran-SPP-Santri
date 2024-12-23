<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Laporan Pendapatan') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h3 class="text-lg font-semibold">Filter Pendapatan</h3>
                    <form method="GET" action="{{ route('payments.earnings') }}" class="flex flex-wrap space-x-4 mt-4">
                        <div class="w-full sm:w-auto">
                            <label for="month" class="block text-sm font-medium text-gray-700">Bulan</label>
                            <select name="month" id="month" class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                                <option value="">Semua Bulan</option>
                                @foreach (range(1, 12) as $m)
                                    <option value="{{ $m }}" {{ request('month') == $m ? 'selected' : '' }}>
                                        {{ \Carbon\Carbon::createFromDate(null, $m, 1)->translatedFormat('F') }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="w-full sm:w-auto">
                            <label for="year" class="block text-sm font-medium text-gray-700">Tahun</label>
                            <input type="number" name="year" id="year" class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                                   value="{{ request('year') }}" placeholder="Contoh: {{ date('Y') }}" aria-label="Filter Tahun">
                        </div>
                        <div class="pt-5">
                            <button type="submit" class="px-6 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                                Filter
                            </button>
                        </div>
                    </form>

                    <div class="mt-6">
                        <h3 class="text-lg font-semibold">Pendapatan</h3>
                        <p class="mt-1 text-gray-600">
                            Bulan: {{ $selectedMonth ?? 'Semua Bulan' }}, Tahun: {{ $selectedYear ?? 'Semua Tahun' }}
                        </p>
                        <p class="mt-1 text-lg font-bold">
                            Pendapatan Bulanan: {{ $monthlyEarnings ? 'Rp' . number_format($monthlyEarnings, 2, ',', '.') : '-' }}
                        </p>
                        <p class="mt-1 text-lg font-bold">
                            Pendapatan Tahunan: {{ $yearlyEarnings ? 'Rp' . number_format($yearlyEarnings, 2, ',', '.') : '-' }}
                        </p>
                    </div>

                    <div class="mt-6">
                        <h3 class="text-lg font-semibold">Rincian Pembayaran</h3>
                        <table class="min-w-full divide-y divide-gray-200 mt-4 border border-gray-300">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Bulan</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tahun</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Jumlah</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse ($payments as $payment)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $payment->paid_month }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $payment->paid_year }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">Rp{{ number_format($payment->amount, 2, ',', '.') }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="px-6 py-4 text-center text-gray-500">Tidak ada data pembayaran untuk bulan dan tahun yang dipilih</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                        <div class="mt-4">
                            {{ $payments->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
