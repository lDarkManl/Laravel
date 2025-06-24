<?php
namespace src\Models;

class User extends Model
{
    protected const string TABLE = 'users';
    protected array $fillable = ['name', 'email', 'status_id'];
}