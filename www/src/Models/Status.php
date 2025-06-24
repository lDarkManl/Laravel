<?php
namespace src\Models;

class Status extends Model
{
    protected const string TABLE = 'statuses';
    protected array $fillable = ['name'];
}