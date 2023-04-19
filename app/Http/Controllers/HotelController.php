<?php

namespace App\Http\Controllers;

use App\Models\Hotel;
use Illuminate\Http\Request;

class HotelController extends Controller
{
    public function index()
    {
        $hotels = Hotel::all();
        return response()->json([
            'status' => 200,
            'message' => $hotels,
        ]);
    }
    public function store(Request $request)
    {
        $hotels = new Hotel();
        $hotels->id = $request->input('id');
        $hotels->nom = $request->input('nom');
        $hotels->localisation = $request->input('localisation');
        $hotels->prix = $request->input('prix');

        $hotels->étoiles = $request->input('étoiles');
        $hotels->image = $request->input('image');
        $hotels->save();
        return response()->json([
            'status' => 200,
            'message' => 'hotels added succesuffly',
        ]);
    }
    public function edit($id)
    {
        $hotels = Hotel::find($id);
        return response()->json([
            'status' => 200,
            'hotel' => $hotels,
        ]);
    }
    public function update(Request $request, $id)
    {

        $hotels =  Hotel::find($id);
        $hotels->id = $request->input('id');

        $hotels->nom = $request->input('nom');
        $hotels->localisation = $request->input('localisation');
        $hotels->prix = $request->input('prix');

        $hotels->étoiles = $request->input('étoiles');
        $hotels->image = $request->input('image');
        $hotels->update();
        return response()->json([
            'status' => 200,
            'message' => 'hotel updated succesuffly',
        ]);
    }
    public function delete($id)
    {
        $hotels = Hotel::find($id);
        $hotels->delete();
        return response()->json([
            'status' => 200,
            'message' => 'hotel deleted succesuffly',
        ]);
    }
}
