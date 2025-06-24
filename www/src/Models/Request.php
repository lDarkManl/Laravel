<?php
namespace src\Models;

class Request extends Model
{
    protected const string TABLE = 'requests';
    protected array $fillable = ['email', 'age', 'status_id'];

}