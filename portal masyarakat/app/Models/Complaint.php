<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Complaint extends Model
{
    use HasFactory;

    public const STATUSES = [
        'pending' => 'Menunggu',
        'in_progress' => 'Diproses',
        'resolved' => 'Selesai',
        'rejected' => 'Ditolak',
    ];

    public const CATEGORIES = [
        'infrastructure' => 'Infrastruktur',
        'environment' => 'Lingkungan',
        'public_services' => 'Pelayanan publik',
        'security' => 'Keamanan',
        'other' => 'Lainnya',
    ];

    public const PRIORITIES = [
        'low' => 'Rendah',
        'normal' => 'Normal',
        'high' => 'Tinggi',
    ];

    protected $fillable = [
        'user_id',
        'subject',
        'category',
        'description',
        'status',
        'priority',
        'admin_response',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
