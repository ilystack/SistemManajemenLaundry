<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ActivityLog extends Model
{
    protected $fillable = [
        'type',
        'description',
        'user_id',
        'user_name',
        'icon',
        'color',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Create activity log helper
     */
    public static function log(string $type, string $description, ?int $userId = null, ?string $userName = null, string $icon = '📝', string $color = 'blue'): void
    {
        self::create([
            'type' => $type,
            'description' => $description,
            'user_id' => $userId,
            'user_name' => $userName,
            'icon' => $icon,
            'color' => $color,
        ]);
    }
}
