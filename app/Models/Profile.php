<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;

class Profile extends Model
{
    protected $fillable = ['name', 'email', 'status_id'];

    public function tableExists(): bool
    {
        return Schema::hasTable($this->getTable());
    }

    public function status()
    {
        return $this->belongsTo(Status::class);
    }
}
