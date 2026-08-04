<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Lead extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $guarded = [];
    protected $table = 'leads';
    public $timestamps = true;

    /** Trạng thái lead chuẩn hoá dùng chung cho filter + view */
    public const STATUSES = [
        'new' => 'Mới',
        'contacted' => 'Đã liên hệ',
        'qualified' => 'Tiềm năng',
        'won' => 'Chốt thành công',
        'lost' => 'Không chốt',
    ];

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }
}
