<?php

namespace App\Http\Controllers;

use App\Models\Property;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PropertyController extends Controller
{
    /**
     * Get all properties.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $properties = Property::with('landlord', 'propertyType')->orderBy('created_at', 'desc')->get();

        return response()->json([
            'message' => "Properties retrieved successfully.",
            'properties' => $properties,
        ]);
    }

    /**
     * Get a property.
     *
     * @param  int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $property = Property::with('landlord', 'propertyType')->findOrFail($id);

        return response()->json([
            'message' => "Property retrieved successfully.",
            'property' => $property,
        ]);
    }

    /**
     * Create a new property.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string',
            'description' => 'required|string',
            'address' => 'required|string',
            'rent' => 'required|numeric',
            'area' => 'numeric',
            'no_of_bedrooms' => 'integer',
            'no_of_bathrooms' => 'integer',
            'type_id' => 'required|integer|exists:property_types,id',
            'image' => 'image|mimes:jpeg,png,jpg|max:10240',
        ],[
            'type_id.exists' => 'The selected property type is invalid.',
            'image.image' => 'The image must be a file of type: jpeg, png, jpg.',
            'image.mimes' => 'The image must be a file of type: jpeg, png, jpg.',
            'image.max' => 'The image must not be greater than 10MB.',
        ]);

        // Populate created_by with the authenticated user's id and create the property
        $request['created_by'] = $request->user()->id;

        // Remove the image from the request data
        $data = $request->except('image');

        // Upload image if it exists
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = Str::uuid() . time() . '.' . $image->extension();
            $image->storeAs('property_images', $imageName, 'public');
            $data['image'] = $imageName;
        }
        $property = Property::create($data);

        return response()->json([
            'message' => "Property created successfully.",
            'property' => $property,
        ]);
    }

    /**
     * Update a property.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'string',
            'description' => 'string',
            'address' => 'string',
            'rent' => 'numeric',
            'area' => 'numeric',
            'no_of_bedrooms' => 'integer',
            'no_of_bathrooms' => 'integer',
            'type_id' => 'integer|exists:property_types,id',
            'image' => 'image|mimes:jpeg,png,jpg|max:10240',
        ],[
            'type_id.exists' => 'The selected property type is invalid.',
            'image.image' => 'The image must be a file of type: jpeg, png, jpg.',
            'image.mimes' => 'The image must be a file of type: jpeg, png, jpg.',
            'image.max' => 'The image must not be greater than 10MB.',
        ]);

        $property = Property::findOrFail($id);

        // Check if the authenticated user is the owner of the property
        if(auth()->user()->role_id != 1 && $property->created_by != auth()->id()) {
            return response()->json([
                'message' => "You are not authorized to update this property.",
            ], 403);
        }

        // Remove the image from the request data
        $data = $request->except('image');

        // Upload image if it exists
        if ($request->hasFile('image')) {
            // Delete the old image
            if ($property->image) {
                Storage::disk('public')->delete('property_images/' . $property->image);
            }
            $image = $request->file('image');
            $imageName = Str::uuid() . time() . '.' . $image->extension();
            $image->storeAs('property_images', $imageName, 'public');
            $data['image'] = $imageName;
        }

        $property->update($data);

        return response()->json([
            'message' => "Property updated successfully.",
            'property' => $property,
        ]);
    }

    /**
     * Delete a property.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function delete($id)
    {
        $property = Property::findOrFail($id);

        // Check if the authenticated user is the owner of the property
        if(auth()->user()->role_id != 1 && $property->created_by != auth()->id()) {
            return response()->json([
                'message' => "You are not authorized to delete this property.",
            ], 403);
        }

        // Delete the image
        if ($property->image) {
            Storage::disk('public')->delete('property_images/' . $property->image);
        }

        // Delete the property
        $property->delete();

        return response()->json([
            'message' => "Property deleted successfully.",
        ]);
    }
}
