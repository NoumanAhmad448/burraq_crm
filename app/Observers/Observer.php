<?php

namespace App\Observers;

use App\Models\ActivityLog;

class Observer
{
    public const CREATED = 'created';
    public const UDPATED = 'updated';
    public const DELETED = 'deleted';
    public const ACTIVED = 'activated';

    public static function logActivity(array $data, $model, $md = [])
    {

        $metadata = array_merge(
            [
                'ip' => request()->ip(),
                'url' => request()->fullUrl(),
            ],
            $md
        );
        $original = $model->getOriginal();
        $new = $model->getAttributes();

        self::updateLogic($data, $model, $new);

        $data['old_values'] = $original;
        $data['new_values'] = $new;
        $data['metadata'] = $metadata;

        ActivityLog::create(array_merge([
            'user_id'  => auth()->id() ?? null,
            'model_id' => $model->id,
        ], $data));
    }

    private static function updateLogic($data, $model, $new, $original)
    {
        if ($data['action'] === self::UDPATED) {

            // Soft delete / restore handling
            if (in_array('Illuminate\Database\Eloquent\SoftDeletes', class_uses($model))) {
                if (is_null($original['deleted_at']) && !is_null($new['deleted_at'])) {
                    $data['action'] = self::DELETED;
                } elseif (!is_null($original['deleted_at']) && is_null($new['deleted_at'])) {
                    $data['action'] = self::ACTIVED;
                }
            } elseif ($model->isDirty('is_deleted')) {
                $data['action'] = $model->is_deleted ? 'deleted' : self::ACTIVED;
            }
        }
    }
}
