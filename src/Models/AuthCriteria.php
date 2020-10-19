<?php

namespace Marqant\AuthGraphQL\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Criteria, for authenticating a model.
 *
 * Class AuthCriteria
 * @package Marqant\AuthGraphQL\Models
 *
 * @mixin Model
 */
class AuthCriteria extends Model
{
    // Make sure nothing assumes that the key is auto-incrementing (its a string)
    public $incrementing = false;

    // Specify that it is a string
    protected $keyType = 'string';

    // Make sure eloquent doesn't assume that we're using timestamps
    public $timestamps = false;

    // Allow auto-fill for eloquent creation
    protected $fillable = [
        'id',
        'credentials',
    ];

    // Make sure these will never get serialized
//    protected $hidden = [
//        'id',
//        'credentials',
//    ];

//    protected $attributes = [
//        'id',
//        'credentials',
//    ];

    // Specify the type, because...
    protected $casts = [
        'credentials' => 'string', // ... eloquent sometimes thinks a string with digits is actually a date/datetime.
    ];

    /**
     * The Model instance, to which this AuthCriteria belongs.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function model()
    {
        return $this->morphTo('authcriteria');
    }
}
