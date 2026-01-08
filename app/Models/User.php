<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
use Laravel\Fortify\TwoFactorAuthenticatable;
// Removed Filament interfaces

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, TwoFactorAuthenticatable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'username',
        'sponsor_id',
        'email',
        'password',
        'is_admin',
        'is_seller',
        'is_online',
        'last_login_at',
        'last_login_ip',
        'hashed_id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'two_factor_secret',
        'two_factor_recovery_codes',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_admin' => 'boolean',
            'is_seller' => 'boolean',
            'is_online' => 'boolean',
            'last_login_at' => 'datetime',
            'last_login_ip' => 'string',
        ];
    }
    /**
     * Sanitize username to lowercase alphanumeric, max 25 chars.
     */
    public function setUsernameAttribute($value): void
    {
        $sanitized = Str::lower(preg_replace('/[^a-z0-9]/', '', (string) $value));
        $this->attributes['username'] = substr($sanitized, 0, 25);
    }

    /**
     * Get the user's initials
     */
    public function initials(): string
    {
        return Str::of($this->username)
            ->explode(' ')
            ->take(2)
            ->map(fn ($word) => Str::substr($word, 0, 1))
            ->implode('');
    }

    public function profile()
    {
        return $this->hasOne(Profile::class);
    }

    public function sponsor()
    {
        return $this->belongsTo(self::class, 'sponsor_id');
    }

    public function addresses()
    {
        return $this->hasMany(Address::class);
    }

    /**
     * The user's default address (is_default = true).
     */
    public function defaultAddress()
    {
        return $this->hasOne(Address::class)->where('is_default', true);
    }

    /**
     * Helper to get a default address, or first available.
     */
    public function defaultAddressOrFirst(): ?Address
    {
        return $this->defaultAddress()->first() ?? $this->addresses()->first();
    }

    /**
     * Set the provided address as default and unset others.
     */
    public function setDefaultAddress(Address $address): void
    {
        $this->addresses()->update(['is_default' => false]);
        $address->forceFill(['is_default' => true])->save();
    }

    protected static function booted(): void
    {
        static::created(function (self $user) {
            if (! $user->profile()->exists()) {
                $user->profile()->create([]);
            }
            // Populate hashed_id after the model has an ID
            if (empty($user->hashed_id) && function_exists('hash_id')) {
                $user->forceFill(['hashed_id' => hash_id($user->id)])
                    ->saveQuietly();
            }
        });
    }

    // Filament-specific methods removed
}
