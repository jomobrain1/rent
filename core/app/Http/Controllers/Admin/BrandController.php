<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use Illuminate\Http\Request;

class BrandController extends Controller
{
    public function index()
    {
        $brands = Brand::latest()->paginate(getPaginate());
        $pageTitle = 'Brands';
        $empty_message = 'No brand has been added.';
        return view('admin.brand.index', compact('pageTitle', 'empty_message', 'brands'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|unique:brands,name'
        ]);

        $brand = new Brand();
        $brand->name = $request->name;
        $brand->save();

        $notify[] = ['success', 'Brand Added'];
        return back()->withNotify($notify);
    }

    public function update(Request $request, $id)
    {
        $brand = Brand::findOrFail($id);

        $request->validate([
            'name' => 'required|string|unique:brands,name,'.$brand->id
        ]);

        $brand->name = $request->name;
        $brand->save();

        $notify[] = ['success', 'Brand Updated'];
        return back()->withNotify($notify);
    }

    public function status(Brand $brand)
    {
        $brand->status = ($brand->status ? 0 : 1);
        $brand->save();

        $notify[] = ['success', 'Brand '. ($brand->status ? 'Activated!' : 'Deactivated!')];
        return back()->withNotify($notify);
    }
}
