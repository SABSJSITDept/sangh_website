<?php

namespace App\Providers;

use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event to listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
    ];

    /**
     * Register any events for your application.
     */
    public function boot(): void
    {
        Event::listen('eloquent.*', function ($event, $data) {
            if (preg_match('/^eloquent\.(created|updated|deleted): (.+)$/', $event, $matches)) {
                $action = $matches[1];
                $modelClass = $matches[2];

                // Prevent infinite loop if logging the AuditLog model itself
                if ($modelClass === \App\Models\AuditLog::class) {
                    return;
                }

                $model = $data[0];

                $userId = auth()->check() ? auth()->id() : null;
                $userName = auth()->check() ? (auth()->user()->name ?? 'User #' . auth()->id()) : 'System/Guest';

                $oldValues = null;
                $newValues = null;
                $hidden = method_exists($model, 'getHidden') ? $model->getHidden() : [];

                if ($action === 'created') {
                    $attributes = $model->getAttributes();
                    $newValues = array_diff_key($attributes, array_flip($hidden));
                } elseif ($action === 'updated') {
                    $changes = $model->getChanges();
                    $oldValues = [];
                    $newValues = [];
                    foreach ($changes as $key => $newValue) {
                        if ($key === 'updated_at') continue;
                        if (in_array($key, $hidden)) continue;

                        $oldValues[$key] = $model->getOriginal($key);
                        $newValues[$key] = $newValue;
                    }
                    if (empty($oldValues) && empty($newValues)) {
                        return; // No actual user field updates
                    }
                } elseif ($action === 'deleted') {
                    $attributes = $model->getAttributes();
                    $oldValues = array_diff_key($attributes, array_flip($hidden));
                }

                try {
                    \App\Models\AuditLog::create([
                        'user_id' => $userId,
                        'user_name' => $userName,
                        'action' => $action,
                        'model_type' => $modelClass,
                        'model_id' => $model->getKey(),
                        'old_values' => $oldValues,
                        'new_values' => $newValues,
                        'ip_address' => app()->runningInConsole() ? null : request()->ip(),
                        'user_agent' => app()->runningInConsole() ? null : request()->userAgent(),
                    ]);
                } catch (\Exception $e) {
                    \Illuminate\Support\Facades\Log::error("Failed to write audit log: " . $e->getMessage());
                }
            }
        });
    }

    /**
     * Determine if events and listeners should be automatically discovered.
     */
    public function shouldDiscoverEvents(): bool
    {
        return false;
    }
}
