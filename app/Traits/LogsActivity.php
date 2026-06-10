<?php

namespace App\Traits;

use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;

trait LogsActivity
{
    protected static function bootLogsActivity()
    {
        static::created(function ($model) {
            static::recordActivity('Create', $model->getKey());
        });

        static::updated(function ($model) {
            static::recordActivity('Update', $model->getKey());
        });

        static::deleted(function ($model) {
            static::recordActivity('Delete', $model->getKey());
        });
    }

    protected static function recordActivity(string $action, $targetId)
    {
        // Hanya mencatat jika yang melakukan aksi adalah admin terautentikasi
        if (Auth::check() && Auth::user()->role === 'admin') {
            $menuName = static::getMenuName();

            ActivityLog::create([
                'user_id' => Auth::id(),
                'user_name' => Auth::user()->name,
                'menu' => $menuName,
                'action' => $action,
                'target_id' => $targetId,
            ]);
        }
    }

    protected static function getMenuName(): string
    {
        $className = class_basename(static::class);

        // Pemetaan nama model ke nama menu yang ramah dibaca
        return match ($className) {
            'Category' => 'Category',
            'Video' => 'Video',
            'User' => 'User',
            'AccessRequest' => 'Access Request',
            default => $className,
        };
    }
}
