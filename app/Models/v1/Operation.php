<?php

namespace App\Models\v1;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Authenticatable;

class Operation extends Model
{
    public $table = "operations";

    public $primaryKey = "id";

    public $timestamps = true;

    public $incrementing = false;

    public $fillable = ['name'];


    public static $rules = [
        'name' => 'required'
    ];

    /**
     * Allow to map column/attribute names
     * @var associative array
     */
    protected static $mappingAttributes = [
    ];

    /**
     * gets all sweeps for the operation.
     */
    public function sweeps()
    {
        return $this->hasMany(Sweep::class);
    }
}
