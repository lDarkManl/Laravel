<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Request extends Model
{
    protected $fillable = ['email', 'age', 'status_id'];

    public $timestamps = false;

    public function getTableColumns(): array
    {
        return \Schema::getColumnListing($this->getTable());
    }
}
