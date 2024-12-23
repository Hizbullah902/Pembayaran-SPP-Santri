<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Student;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\View\View;

class PaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, Student $student): View
    {
        return view('pages.payment.index', [
            'payments' => Payment::render($request->search, $student->nisn),
            'student' => $student,
            'search' => $request->search,
        ]);
    }

    /**
     * Generate PDF report for payments.
     */
    public function print(Request $request, Student $student): Response
    {
        $pdf = Pdf::loadView('pages.payment.print', [
            'title' => 'Laporan Pembayaran SPP',
            'student' => $student,
            'payments' => Payment::where('nisn', $student->nisn)->get(),
        ]);
        $pdf->setPaper('A4', 'landscape');
        return $pdf->download('laporan_spp_' . $student->nisn . '.pdf');
    }

    /**
     * Show form for creating a new payment.
     */
    public function create(Student $student): View
    {
        $student->load(['grade', 'fee']);
        return view('pages.payment.create', [
            'student' => $student,
            'month' => [
                'Januari', 'Februari', 'Maret', 'April',
                'Mei', 'Juni', 'Juli', 'Agustus',
                'September', 'Oktober', 'November', 'Desember',
            ],
        ]);
    }

    /**
     * Store a newly created payment in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $data = $request->all();
        try {
            $exists = Payment::where('nisn', $data['nisn'])
                ->where('paid_year', $data['paid_year'])
                ->where('paid_month', $data['paid_month'])->count();
            if ($exists) {
                throw new \Exception('Sudah lunas.');
            }
            $data['user_id'] = auth()->user()->id;
            $data['paid_at'] = now();
            Payment::create($data);
            return redirect()->route('payment.index', $data['nisn'])->with('status', 'success')->with('message', 'Berhasil.');
        } catch (\Throwable $exception) {
            return redirect()->route('payment.index', $data['nisn'])->with('status', 'failed')->with('message', $exception->getMessage());
        }
    }

    /**
     * Display the earnings report based on month and year.
     */
    public function earnings(Request $request)
    {
        $month = $request->get('month'); // Bulan yang difilter
        $year = $request->get('year'); // Tahun yang difilter

        // Query dasar untuk pembayaran
        $paymentsQuery = Payment::query();

        if ($month) {
            $paymentsQuery->where('paid_month', $month); // Filter bulan
        }

        if ($year) {
            $paymentsQuery->where('paid_year', $year); // Filter tahun
        }

        // Nama bulan
        $monthNames = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember',
        ];

        // Data pembayaran dengan pagination
        $payments = $paymentsQuery->paginate(10);

        // Hitung pendapatan bulanan dan tahunan
        $monthlyEarnings = $month ? $paymentsQuery->sum('amount') : 0;
        $yearlyEarnings = $year ? Payment::where('paid_year', $year)->sum('amount') : null;


        return view('pages.payment.earnings', [
            'payments' => $payments,
            'monthlyEarnings' => $monthlyEarnings,
            'yearlyEarnings' => $yearlyEarnings,
            'selectedMonth' => $month ? $monthNames[$month] : null,
            'selectedYear' => $year,
        ]);
    } 
}
