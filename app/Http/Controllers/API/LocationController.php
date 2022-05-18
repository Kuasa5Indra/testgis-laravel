<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Helpers\ClientResponse;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Location;


class LocationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $locations = Location::where('user_id', auth()->id())->get();
        return ClientResponse::successResponse(Response::HTTP_OK, 'Success get locations', $locations);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->only(['place', 'city', 'address', 'latitude', 'longitude']);
        if($request->user()->cannot('create', Location::class)){
            return ClientResponse::errorResponse(Response::HTTP_FORBIDDEN, 'You are not allowed to create resource');
        }
        $location = Location::create([
            'place' => $data['place'],
            'city' => $data['city'],
            'address' => $data['address'],
            'latitude' => $data['latitude'],
            'longitude' => $data['longitude'],
            'user_id' => auth()->id()
        ]);
        return ClientResponse::successResponse(Response::HTTP_OK, 'Success create location', $location);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
        $location = Location::findOrFail($id);
        if($request->user()->cannot('view', $location)){
            return ClientResponse::errorResponse(Response::HTTP_FORBIDDEN, 'You are not allowed to see this resource');
        }
        return ClientResponse::successResponse(Response::HTTP_OK, 'Success get location', $location);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $data = $request->only(['place', 'city', 'address', 'latitude', 'longitude']);
        $location = Location::findOrFail($id);
        if($request->user()->cannot('update', $location)){
            return ClientResponse::errorResponse(Response::HTTP_FORBIDDEN, 'You are not allowed to update this resource');
        }
        $location->update($data);
        return ClientResponse::successResponse(Response::HTTP_OK, 'Success update location', $location);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        $location = Location::findOrFail($id);
        if($request->user()->cannot('delete', $location)){
            return ClientResponse::errorResponse(Response::HTTP_FORBIDDEN, 'You are not allowed to delete this resource');
        }
        $location->delete();
        return ClientResponse::successResponse(Response::HTTP_OK, 'Success delete location');
    }
}
