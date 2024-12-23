<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model
{
    use HasFactory;

    // Daftar kolom yang dapat diisi secara massal
    protected $fillable = [
        'paid_at',
        'amount',
        'paid_month',
        'paid_year',
        'nisn',
        'user_id',
        'school_fee_id',
        'payment_status',
        'payment_note',
        'payment '
    ];

    // Default nilai untuk atribut
    protected $attributes = [
        'payment_status' => 'pending',
        'payment_note' => '',
    ];

    // Relasi ke tabel students berdasarkan nisn
    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class, 'nisn', 'nisn');
    }

    // Relasi ke tabel users berdasarkan user_id
    public function staff(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    // Relasi ke tabel school_fees berdasarkan school_fee_id
    public function fee(): BelongsTo
    {
        return $this->belongsTo(SchoolFee::class, 'school_fee_id', 'id');
    }

    // Scope untuk pencarian data pembayaran
    public function scopeSearch($query, $search)
    {
        return $query->when($search, function ($query, $find) {
            return $query
                ->where('nisn', 'like', "%{$find}%")
                ->orWhere('paid_month', 'like', "%{$find}%")
                ->orWhere('paid_year', 'like', "%{$find}%");
        });
    }

    // Scope untuk merender data berdasarkan kriteria tertentu
    public function scopeRender($query, $search, $key, $perPage = 5)
    {
        return $query
            ->with(['staff', 'fee', 'student'])
            ->search($search)
            ->where('nisn', $key)
            ->latest()
            ->paginate($perPage)
            ->appends([
                'search' => $search,
            ]);
    }

    // Scope untuk menghitung pendapatan berdasarkan bulan dan tahun
    public function scopeEarningsByMonth($query, $month, $year)
    {
        return $query
            ->where('paid_month', $month)
            ->where('paid_year', $year)
            ->where('payment_status', 'completed') // Hanya menghitung pembayaran selesai
            ->sum('amount');
    }

    // Scope untuk menghitung pendapatan tahunan
    public function scopeEarningsByYear($query, $year)
    {
        return $query
            ->where('paid_year', $year)
            ->where('payment_status', 'completed') // Hanya menghitung pembayaran selesai
            ->sum('amount');
    }

    // Accessor untuk memformat jumlah pembayaran ke mata uang
    public function getFormattedAmountAttribute()
    {
        return 'Rp' . number_format($this->amount, 2, ',', '.');
    }

    // Accessor untuk nama bulan dalam bahasa Indonesia
    public function getFormattedMonthAttribute()
    {
        $monthNames = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
        ];

        return $monthNames[$this->paid_month] ?? $this->paid_month;
    }
}
