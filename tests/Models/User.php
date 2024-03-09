<?php

namespace Tests\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Tests\database\factories\UserFactory;

class User extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    protected static function newFactory(): UserFactory
    {
        return UserFactory::new();
    }
}
