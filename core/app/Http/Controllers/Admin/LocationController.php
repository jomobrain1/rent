<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Location;
use Illuminate\Http\Request;

class LocationController extends Controller
{
    public function index()
    {
        $locations = Location::latest()->paginate(getPaginate());
        $pageTitle = 'Locations';
        $empty_message = 'No location has been added.';
        return view('admin.location.index', compact('pageTitle', 'empty_message', 'locations'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|unique:locations,name'
        ]);

        $location = new Location();
        $location->name = $request->name;
        $location->save();

        $notify[] = ['success', 'Location Added'];
        return back()->withNotify($notify);
    }

    public function update(Request $request, $id)
    {
        $location = Location::findOrFail($id);

        $request->validate([
            'name' => 'required|string|unique:locations,name,'.$location->id
        ]);

        $location->name = $request->name;
        $location->save();

        $notify[] = ['success', 'Location Updated'];
        return back()->withNotify($notify);
    }

    public function status(location $location)
    {
        $location->status = ($location->status ? 0 : 1);
        $location->save();

        $notify[] = ['success', 'Location '. ($location->status ? 'Activated!' : 'Deactivated!')];
        return back()->withNotify($notify);
    }
}
