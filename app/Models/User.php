<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;

class User extends Model implements AuthenticatableContract
{
    use Authenticatable;

    public $table = "users";

    public $primaryKey = "id";

    public $timestamps = true;

    public $incrementing = false;

    public $fillable = ['name', 'email', 'password'];

    protected $hidden = ['password'];

    public static $rules = [
        'name' => 'required',
        'email' => 'required|email|unique:users',
        'password' => 'required|min:6'
    ];

    /**
     * Allow to map column/attribute names
     * @var associative array
     */
    protected static $mappingAttributes = [

    ];
}
