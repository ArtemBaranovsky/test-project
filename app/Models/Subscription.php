<?php

namespace App\Models;

use App\Models\SubscriptionPlan;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Carbon;

class Subscription extends Model
{
    use HasFactory;

    public const STATUS_ACTIVE = 'active';
    public const STATUS_INACTIVE = 'inactive';
    public const STATUS_SUSPENDED = 'suspended';
    public const STATUS_CANCELED = 'canceled';
    public const STATUS_DELETED = 'deleted';

    public const STATUSES = [
        self::STATUS_ACTIVE,
        self::STATUS_INACTIVE,
        self::STATUS_SUSPENDED,
        self::STATUS_CANCELED,
        self::STATUS_DELETED,
    ];

    protected $fillable = ['name', 'publications_available', 'active'];

    protected $dates = ['expires_at'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return HasMany
     */
    public function publications(): HasMany
    {
        return $this->hasMany(Publication::class, 'user_id', 'user_id');
    }

    /**
     * @param $query
     * @return void
     */
    public function scopeActive($query): void
    {
        $query->where('active', 1);
    }

    /**
     * @return void
     */
    public function activate(): void
    {
        $this->status = self::STATUS_ACTIVE;
        $this->expires_at = now()->addMonth();
        $this->save();
    }

    /**
     * @return bool
     */
    public function isExpired(): bool
    {
        return $this->expires_at < now();
    }

    /**
     * Activate subscription plan (after the payment)
     * @return void
     */
    public function activatePlan()
    {
        $this->starts_at = now();
        $this->ends_at = now()->addMonth();
        $this->save();
    }


    /**
     * @return bool
     */
    public function isPlanAvailable(): bool
    {
        return $this->isActive() && ! $this->isExpired();
    }

    /**
     * @return void
     */
    public function cancelUnusedPublications(): void
    {
        $unusedPublications = $this->publications_available - $this->user->publications()->count();

        if ($unusedPublications > 0) {
            $this->publications_available -= $unusedPublications;
            $this->save();
        }
    }

    public function subscriptionPlan(): BelongsTo
    {
        return $this->belongsTo(SubscriptionPlan::class, 'plan_id');
    }

    public function isActive(): bool
    {
        return $this->status === self::STATUS_ACTIVE;
    }

    public function isInactive(): bool
    {
        return $this->status === self::STATUS_INACTIVE;
    }

    public function isSuspended(): bool
    {
        return $this->status === self::STATUS_SUSPENDED;
    }

    public function isCanceled(): bool
    {
        return $this->status === self::STATUS_CANCELED;
    }

    public function isDeleted(): bool
    {
        return $this->status === self::STATUS_DELETED;
    }

    public function getStartDate(): Carbon
    {
        return $this->created_at;
    }

    public function getEndDate(): Carbon
    {
        $startDate = $this->getStartDate();
        $endDate = $startDate->addMonth();

        return $endDate;
    }
}
