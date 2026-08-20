<?php
// app/Models/RiwayatPembayaran.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RiwayatPembayaran extends Model
{
    protected $fillable = [
        'siswa_id', 'paket_id', 'tipe_paket_snapshot', 'bukti_transfer',
        'payment_method', 'jumlah_sesi', 'total_harga', 'status', 'approved_at',
    ];

    protected $casts = ['approved_at' => 'datetime'];

    public function siswa()
    {
        return $this->belongsTo(Siswa::class);
    }

    public function paket()
    {
        return $this->belongsTo(PaketBelajar::class, 'paket_id');
    }
}
?>