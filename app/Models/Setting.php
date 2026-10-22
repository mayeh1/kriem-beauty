<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Setting extends Model
{
    protected $fillable = ['group', 'key', 'label', 'type', 'value', 'sort_order'];

    public static function get(string $key, string $default = ''): string
    {
        return Cache::remember("setting.{$key}", 300, function () use ($key, $default) {
            return static::where('key', $key)->value('value') ?? $default;
        });
    }

    public static function set(string $key, ?string $value): void
    {
        static::where('key', $key)->update(['value' => $value]);
        Cache::forget("setting.{$key}");
    }

    public static function clearCache(): void
    {
        static::pluck('key')->each(fn ($key) => Cache::forget("setting.{$key}"));
    }
}
