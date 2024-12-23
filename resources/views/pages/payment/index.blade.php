<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Pembayaran SPP ' . $student->name . ' [' . $student->nisn . ']') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="flex flex-col">
                    <div class="overflow-x-auto sm:-mx-6 lg:-mx-8">
                        <div class="py-2 inline-block min-w-full sm:px-6 lg:px-8">

                            @if(isset($monthlyEarnings) && isset($yearlyEarnings))
                                <div class="mt-6">
                                    <h3 class="text-lg font-semibold">Pendapatan</h3>
                                    <div class="mt-4">
                                        <p><strong>Pendapatan Bulan {{ $selectedMonth }} Tahun {{ $selectedYear }}:</strong> Rp{{ number_format($monthlyEarnings, 0, ',', '.') }}</p>
                                        <p><strong>Pendapatan Tahun {{ $selectedYear }}:</strong> Rp{{ number_format($yearlyEarnings, 0, ',', '.') }}</p>
                                    </div>
                                </div>
                            @endif

                            <div class="overflow-hidden mt-6 mb-6">
                                <table class="min-w-full w-full">
                                    <thead class="border-b">
                                    <tr>
                                        <th scope="col" class="text-sm font-bold text-gray-900 px-6 py-4 text-left">
                                            Tanggal Bayar
                                        </th>
                                        <th scope="col" class="text-sm font-bold text-gray-900 px-6 py-4 text-left">
                                            Dibayar
                                        </th>
                                        <th scope="col" class="text-sm font-bold text-gray-900 px-6 py-4 text-left">
                                            Jumlah Bayar
                                        </th>
                                        <th scope="col" class="text-sm font-bold text-gray-900 px-6 py-4 text-left">
                                            Petugas
                                        </th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($payments as $payment)
                                        <tr class="border-b">
                                            <td class="text-sm text-gray-900 font-light px-6 py-4 whitespace-nowrap">
                                                {{ $payment->paid_at }}
                                            </td>
                                            <td class="text-sm text-gray-900 font-light px-6 py-4 whitespace-nowrap">
                                                {{ $payment->paid_month . ' ' . $payment->paid_year }}
                                            </td>
                                            <td class="text-sm text-gray-900 font-light px-6 py-4 whitespace-nowrap">
                                                {{ "Rp" . number_format($payment->amount,2,',','.') }}
                                            </td>
                                            <td class="text-sm text-gray-900 font-light px-6 py-4 whitespace-nowrap">
                                                {{ $payment->staff?->name }}
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>

                            {!! $payments->links() !!}

                            @if (in_array(session('status'), ['success', 'failed']))
                                <p
                                    x-data="{ show: true }"
                                    x-show="show"
                                    x-transition
                                    x-init="setTimeout(() => show = false, 2000)"
                                    class="text-sm text-gray-600 mb-4"
                                >{{ session('message') }}</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
