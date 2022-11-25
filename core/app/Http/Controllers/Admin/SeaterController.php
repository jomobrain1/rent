<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Seater;
use Illuminate\Http\Request;

class SeaterController extends Controller
{
    public function index()
    {
        $seaters = Seater::latest()->get();
        $pageTitle = 'Seat Type';
        $empty_message = 'No data found.';
        return view('admin.seater.index', compact('pageTitle', 'empty_message', 'seaters'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'number' => 'required|integer|gt:0|unique:seaters,number'
        ]);

        $seater = new Seater();
        $seater->number = $request->number;
        $seater->save();

        $notify[] = ['success', 'Added Successfully'];
        return back()->withNotify($notify);
    }

    public function update(Request $request, $id)
    {
        $seater = Seater::findOrFail($id);

        $request->validate([
            'number' => 'required|string|unique:seaters,number,'.$seater->id
        ]);

        $seater->number = $request->number;
        $seater->save();

        $notify[] = ['success', 'Updated Successfully'];
        return back()->withNotify($notify);
    }

    public function status(Seater $seater)
    {
        $seater->status = ($seater->status ? 0 : 1);
        $seater->save();

        $notify[] = ['success', $seater->status ? 'Activated!' : 'Deactivated!'];
        return back()->withNotify($notify);
    }
}
