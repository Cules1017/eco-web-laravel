<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GameConfig extends Model
{
    use HasFactory;

    protected $fillable = ['key', 'value', 'description'];

    public static function get($key, $default = null)
    {
        $config = static::where('key', $key)->first();
        return $config ? $config->value : $default;
    }

    public static function set($key, $value, $description = null)
    {
        return static::updateOrCreate(
            ['key' => $key],
            ['value' => $value, 'description' => $description ?? '']
        );
    }
}
