<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Status extends Model
{
    public function profiles(): HasMany
    {
        return $this->hasMany(Profile::class);
    }

    public function messages(): HasMany
    {
        return $this->hasMany(Message::class);
    }
}
