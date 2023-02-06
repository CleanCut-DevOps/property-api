<?php

namespace App\Http\Controllers;

use App\Http\Middleware\BelongsToAccount;
use App\Http\Middleware\ValidateJWT;
use App\Models\Address;
use App\Models\Property;
use App\Models\Rooms;
use App\Models\RoomType;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Validation\Rule;

class PropertyController extends Controller
{
    public function __construct()
    {
        $this->middleware(ValidateJWT::class);
        $this->middleware(BelongsToAccount::class)->only(['show', 'update', 'destroy']);

        $this->validate('store', [
            'icon' => ['string', 'max:24'],
            'label' => ['string', 'max:255'],
            'description' => ['nullable', 'string', 'max:255'],
            'type_id' => ['nullable', Rule::exists('property_types', 'id')],
            'line_1' => ['nullable', 'string', 'max:255'],
            'line_2' => ['nullable', 'string', 'max:255'],
            'city' => ['nullable', 'string', 'max:255'],
            'state' => ['nullable', 'string', 'max:255'],
            'zip' => ['nullable', 'numeric']
        ], [
            'icon.string' => 'The icon must be a string.',
            'icon.max' => 'The icon must be less than 24 characters.',
            'label.string' => 'The label must be a string.',
            'label.max' => 'The label must be less than 255 characters.',
            'description.string' => 'The description must be a string.',
            'description.max' => 'The description must be less than 255 characters.',
            'type_id.exists' => 'The type_id must be a valid property type.',
            'line_1.string' => 'Address line 1 must be a string.',
            'line_1.max' => 'Address line 1 must be less than 255 characters.',
            'line_2.string' => 'Address line 2 must be a string.',
            'line_2.max' => 'Address line 2 must be less than 255 characters.',
            'city.string' => 'The city must be a string.',
            'city.max' => 'The city must be less than 255 characters.',
            'state.string' => 'The state must be a string.',
            'state.max' => 'The state must be less than 255 characters.',
            'zip.numeric' => 'The zip code must be a number.'
        ]);

        $this->validate('update', [
            'icon' => ['string', 'max:24'],
            'label' => ['string', 'max:255'],
            'favourite' => ['boolean'],
            'description' => ['nullable', 'string', 'max:255'],
            'type_id' => [Rule::exists('property_types', 'id')],
            'line_1' => ['string', 'max:255'],
            'line_2' => ['nullable', 'string', 'max:255'],
            'city' => ['string', 'max:255'],
            'state' => ['nullable', 'string', 'max:255'],
            'zip' => ['numeric'],
            'rooms' => ['nullable', 'array'],
            'rooms.*.id' => ['required_with:rooms', Rule::exists('room_types', 'id')],
            'rooms.*.quantity' => ['required_with:rooms', 'numeric', 'integer']
        ], [
            'icon.string' => 'The icon must be a string.',
            'icon.max' => 'The icon must be less than 24 characters.',
            'label.string' => 'The label must be a string.',
            'label.max' => 'The label must be less than 255 characters.',
            'favourite.boolean' => 'Favourite attribute must be a boolean.',
            'description.string' => 'The description must be a string.',
            'description.max' => 'The description must be less than 255 characters.',
            'type_id.exists' => 'The type_id must be a valid property type.',
            'line_1.string' => 'Address line 1 must be a string.',
            'line_1.max' => 'Address line 1 must be less than 255 characters.',
            'line_2.string' => 'Address line 2 must be a string.',
            'line_2.max' => 'Address line 2 must be less than 255 characters.',
            'city.string' => 'The city must be a string.',
            'city.max' => 'The city must be less than 255 characters.',
            'state.string' => 'The state must be a string.',
            'state.max' => 'The state must be less than 255 characters.',
            'zip.numeric' => 'The zip code must be a number.',
            'rooms.array' => 'The room field is invalid.',
            'rooms.*.id.required_with' => 'The room type is required.',
            'rooms.*.id.exists' => 'The room type does not exist.',
            'rooms.*.quantity.required_with' => 'The quantity of rooms is required.',
            'rooms.*.quantity.numeric' => 'The quantity of rooms must be a valid number.',
            'rooms.*.quantity.integer' => 'The quantity of rooms must be a valid number.'
        ]);
    }

    /**
     * Display a listing of user properties.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        return response()->json([
            'type' => 'Successful request',
            'message' => 'Properties retrieved successfully',
            'properties' => Property::whereUserId(request('user_id'))->get()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        $property = Property::factory()->create($request->only(['icon', 'label', 'description', 'user_id', 'type_id']));

        Address::create([
            ...$request->only(['line_1', 'line_2', 'city', 'state', 'zip']),
            'property_id' => $property->id
        ]);

        return response()->json([
            'type' => 'Successful request',
            'message' => 'Property created successfully',
            'property' => $property->refresh(),
        ], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param string $id
     * @return JsonResponse
     */
    public function show(string $id): JsonResponse
    {
        return response()->json([
            'type' => 'Successful request',
            'message' => 'Property retrieved successfully',
            'property' => Property::whereId($id)->first(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param string $id
     * @return JsonResponse
     */
    public function update(Request $request, string $id): JsonResponse
    {
        $property = Property::whereId($id)->first();

        // Determine if the request contains a given input item key.

        if ($request->has('rooms')) {
            $errors = [];

            foreach (request('rooms') as $room) {
                $roomTypeID = $room['id'];

                if (
                    !RoomType::wherePropertyTypeId($request->has('type_id') ? request('type_id') : $property->type_id)
                    ->whereId($roomTypeID)
                    ->exists()
                ) {
                    $index = array_search($room, request('rooms'));

                    $errors[] = [
                        "rooms.$index.id" => "The room type {$roomTypeID} does not exist."
                    ];
                }


            }

            if (count($errors) > 0) {
                return response()->json([
                    'type' => 'Invalid data',
                    'message' => 'Some room type(s) does not exist for this property type.',
                    'errors' => $errors
                ], 400);
            }
        }

        if ($request->has('type_id')) {
            if (strcasecmp(request('type_id'), $property->type_id)) {
                Rooms::wherePropertyId($property->id)->delete();

                foreach (RoomType::wherePropertyTypeId(request('type_id'))->get() as $room) {
                    Rooms::create(['quantity' => 0, 'type_id' => $room->id, 'property_id' => $property->id]);
                }
            }

        }

        if ($request->has('rooms')) {
            foreach (request('rooms') as $room) {
                $roomTypeID = $room['id'];
                $quantity = $room['quantity'];

                Rooms::wherePropertyId($property->id)->whereTypeId($roomTypeID)->update(['quantity' => $quantity]);
            }
        }

        $property->update($request->only(['icon', 'label', 'description', 'type_id']));

        $property->address->update($request->only(['line_1', 'line_2', 'city', 'state', 'zip']));

        if ($request->has('favourite')) {
            if (request('favourite')) {
                $property->favourite_at = Carbon::now();
            } else {
                $property->favourite_at = null;
            }

            $property->save();
        }

        return response()->json([
            'type' => 'Successful request',
            'message' => 'Property updated successfully'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param string $id
     * @return JsonResponse
     */
    public function destroy(string $id): JsonResponse
    {
        $property = Property::whereId($id);

        foreach (Rooms::wherePropertyId($property->first()->id)->get() as $room) {
            Rooms::wherePropertyId($property->first()->id)->whereTypeId($room->type_id)->delete();
        }

        $property->first()->update(['type_id' => null]);

        $property->first()->delete();

        return response()->json([
            'type' => 'Successful request',
            'message' => 'Property deleted successfully',
        ]);
    }
}
