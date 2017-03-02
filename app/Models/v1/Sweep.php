<?php

namespace App\Models\v1;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Authenticatable;

class Sweep extends Model
{
    public $table = "sweep";

    public $primaryKey = "id";

    public $timestamps = true;

    public $incrementing = false;

    public $fillable = [];


    public static $rules = [
        'path' => 'required|json'
    ];

    /**
     * Allow to map column/attribute names
     * @var associative array
     */
    protected static $mappingAttributes = [
    ];

    /**
     * gets the operation of the sweep.
     */
    public function operation()
    {
        return $this->belongsTo(Operation::class);
    }
}
