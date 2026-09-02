<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['name', 'description', 'price', 'duration', 'image_path', 'is_active'])]
class Service extends Model
{
    use HasFactory, HasUuids;

    /**
     * The accessors to append to the model's array form.
     *
     * @var list<string>
     */
    protected $appends = ['image_url'];

    /**
     * Get the appointments for this service.
     *
     * @return HasMany<Appointment, $this>
     */
    public function appointments(): HasMany
    {
        return $this->hasMany(Appointment::class);
    }

    /**
     * Get the resolved public URL for the service image.
     */
    public function getImageUrlAttribute(): string
    {
        if (! empty($this->image_path)) {
            if (str_starts_with($this->image_path, 'http://') || str_starts_with($this->image_path, 'https://')) {
                return $this->image_path;
            }

            if (str_starts_with($this->image_path, 'images/') || str_starts_with($this->image_path, '/images/')) {
                return asset(ltrim($this->image_path, '/'));
            }

            return asset('storage/'.ltrim($this->image_path, '/'));
        }

        return 'https://images.unsplash.com/photo-1503951914875-452162b0f3f1?auto=format&fit=crop&w=800&q=80';
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'is_active' => 'boolean',
        ];
    }
}
