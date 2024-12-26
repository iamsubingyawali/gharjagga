<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Property extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'title',
        'description',
        'address',
        'rent',
        'image',
        'area',
        'no_of_bedrooms',
        'no_of_bathrooms',
        'created_by',
        'type_id',
    ];

    /**
     * Casts
     */
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'no_of_bedrooms' => 'integer',
        'no_of_bathrooms' => 'integer',
        'rent' => 'float',
        'area' => 'float',
    ];

    /**
     * Get the user that owns the property.
     */
    public function landlord()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the property type that owns the property.
     */
    public function propertyType()
    {
        return $this->belongsTo(PropertyType::class);
    }
}
