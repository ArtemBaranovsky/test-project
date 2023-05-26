<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Role extends Model
{
    use HasFactory;

    const NAME_ADMIN = 'admin';
    const NAME_MODERATOR = 'moderator';
    const NAME_PUBLISHER = 'publisher';

    const NAMES = [
        self::NAME_ADMIN,
        self::NAME_MODERATOR,
        self::NAME_PUBLISHER
    ];
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['name'];

    /**
     * @return BelongsToMany
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'user_role');
    }
}
