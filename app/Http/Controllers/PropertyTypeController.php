<?php

namespace App\Http\Controllers;

use App\Models\PropertyType;
use Illuminate\Http\Request;

class PropertyTypeController extends Controller
{
    /**
     * Get all property types.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $propertyTypes = PropertyType::all();

        return response()->json([
            'message' => "Property types retrieved successfully.",
            'property_types' => $propertyTypes,
        ]);
    }
}
