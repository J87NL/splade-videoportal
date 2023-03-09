<?php

namespace App\Models;

use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Video extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia, LogsActivity, Sluggable, SoftDeletes;

    protected $fillable = [
        'position',
        'slug',
        'title',
        'dance_id',
        'url',
        'path',
    ];

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('order', function (Builder $builder) {
            $builder->orderBy('position', 'asc');
        });
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'title',
            ],
        ];
    }

    public function dance(): BelongsTo
    {
        return $this->belongsTo(Dance::class);
    }

    public function levels(): BelongsToMany
    {
        return $this->belongsToMany(Level::class);
    }

    public function views(): HasMany
    {
        return $this->hasMany(View::class);
    }

    public function getLastMedia(string $collectionName = 'default', $filters = [])
    {
        $media = $this->getMedia($collectionName, $filters);

        return $media->last();
    }

    public function getLastMediaUrl(string $collectionName = 'default', string $conversionName = ''): string
    {
        $media = $this->getLastMedia($collectionName);

        if (! $media) {
            return $this->getFallbackMediaUrl($collectionName, $conversionName) ?: '';
        }

        if ($conversionName !== '' && ! $media->hasGeneratedConversion($conversionName)) {
            return $media->getUrl();
        }

        return $media->getUrl($conversionName);
    }

    public function getVideoSrcAttribute(): ?string
    {
        return ! empty($this->path) ? route('videos.file', $this) : $this->url;
    }

    protected function getVideoTypeAttribute(): string
    {
        if (! empty($this->path)) {
            return 'mp4';
        }

        if (! empty($this->url) && str_contains($this->url, 'youtu')) {
            return 'youtube';
        }

        if (! empty($this->url) && str_contains($this->url, 'vimeo')) {
            return 'vimeo';
        }

        return 'mp4';
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logAll();
    }
}
