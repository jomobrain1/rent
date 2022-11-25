<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Type;
use App\Rules\FileTypeValidate;
use Illuminate\Http\Request;

class TypeController extends Controller
{
    public function index()
    {
        $types = Type::latest()->paginate(getPaginate());
        $pageTitle = 'Vehicle Types';
        $empty_message = 'No vehicle types has been added.';
        return view('admin.type.index', compact('pageTitle', 'empty_message', 'types'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|unique:types,name',
            'image' => ['image',new FileTypeValidate(['jpg','jpeg','png'])]
        ]);

        $type = new Type();
        $type->name = $request->name;
        $path = imagePath()['types']['path'];
        $size = imagePath()['types']['size'];
        if ($request->hasFile('image')) {
            try {
                $filename = uploadImage($request->image, $path, $size);
            } catch (\Exception $exp) {
                $notify[] = ['errors', 'Image could not be uploaded.'];
                return back()->withNotify($notify);
            }
            $type->image = $filename;
        }
        $type->save();

        $notify[] = ['success', 'Vehicle Type Added'];
        return back()->withNotify($notify);
    }

    public function update(Request $request, $id)
    {
        $type = Type::findOrFail($id);

        $request->validate([
            'name' => 'required|string|unique:types,name,'.$type->id,
            'image' => ['image',new FileTypeValidate(['jpg','jpeg','png'])]

        ]);

        $path = imagePath()['types']['path'];
        $size = imagePath()['types']['size'];
        $filename = $type->image;
        if ($request->hasFile('image')) {
            try {
                $filename = uploadImage($request->image, $path, $size, $filename);
            } catch (\Exception $exp) {
                $notify[] = ['errors', 'Image could not be uploaded.'];
                return back()->withNotify($notify);
            }
            $type->image = $filename;
        }
        $type->name = $request->name;
        $type->save();

        $notify[] = ['success', 'Vehicle Type Updated'];
        return back()->withNotify($notify);
    }

    public function status(Type $type)
    {
        $type->status = ($type->status ? 0 : 1);
        $type->save();

        $notify[] = ['success', 'Type '. ($type->status ? 'Activated!' : 'Deactivated!')];
        return back()->withNotify($notify);
    }
}
