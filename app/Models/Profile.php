<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    protected $fillable = ['name', 'email', 'status_id'];

    public $timestamps = false;

    public function getTableColumns(): array
    {
        return \Schema::getColumnListing($this->getTable());
    }
}
