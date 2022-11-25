<?php

namespace App\Http\Controllers\Host;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\RentLog;
use App\Rules\FileTypeValidate;
use App\Models\Seater;
use App\Models\Type;
use App\Models\Vehicle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VehicleController extends Controller {

    public function __construct(){
        $this->activeTemplate = activeTemplate();
    }

    public function index() {
        $brands = Brand::active()->withCount('vehicles')->orderBy('name')->get();
        $seats = Seater::active()->withCount('vehicles')->orderBy('number')->get();
        $types = Type::active()->orderBy('name')->get();

        $vehicles = Vehicle::active()->where('host_id', auth('host')->id())->latest()->paginate(getPaginate());
        $pageTitle = 'All Vehicles';
        return view($this->activeTemplate.'host.vehicles.index',compact('vehicles','pageTitle', 'brands', 'seats', 'types'));
    }
    public function vehicleSearch(Request $request) {
        $brands = Brand::active()->withCount('vehicles')->orderBy('name')->get();
        $seats = Seater::active()->withCount('vehicles')->orderBy('number')->get();
        $types = Type::active()->withCount('vehicles')->orderBy('name')->get();


        $vehicles = Vehicle::active()->where('host_id', Auth::guard('host')->id())->with('rents');
        $pageTitle = 'Vehicle Search';

        if ($request->name) {
            $vehicles->where('name', 'LIKE', "%{$request->name}%");
        }

        if ($request->brand) {
            $vehicles->where('brand_id', $request->brand);
        }

        if ($request->seats){
            $vehicles = $vehicles->orWhere('seater_id', $request->seats);
        }

        if ($request->types){
            $vehicles = $vehicles->orWhere('type_id', $request->types);
        }

        if ($request->model){
            $vehicles->orWhere('model', 'LIKE', "%$request->model%");
        }

        if ($request->min_price){
            $vehicles->where('price', '>=', $request->min_price);
        }

        if ($request->max_price){
            $vehicles->where('price', '<=', $request->max_price);
        }

        $vehicles = $vehicles->latest()->paginate(6)->withQueryString();

        return view($this->activeTemplate.'host.vehicles.index',compact('vehicles','pageTitle', 'brands', 'seats', 'types'));
    }

    public function add() {
        $pageTitle = 'Add vehicle';
        $brands = Brand::active()->orderBy('name')->get();
        $seaters = Seater::active()->orderBy('number')->get();
        $types = Type::active()->orderBy('name')->get();

        return view($this->activeTemplate.'host.vehicles.add', compact('pageTitle', 'brands', 'seaters', 'types'));
    }

    public function store(Request $request) {
        $request->validate([
            'name' => 'required|string',
            'brand' => 'required|integer|gt:0',
            'seater' => 'required|integer|gt:0',
            'type' => 'required|integer|gt:0',
            'price' => 'required|numeric|gt:0',
            'details' => 'required|string',
            'model' => 'required|string',
            'doors' => 'required|integer|gt:0',
            'transmission' => 'required|string',
            'fuel_type' => 'required|string',
            'images.*' => ['required', 'max:10000', new FileTypeValidate(['jpeg','jpg','png','gif'])],
            'icon' => 'array',
            'icon.*' => 'string',
            'label' => 'required|array',
            'label.*' => 'string',
            'value' => 'array',
            'value.*' => 'string',
            'area' => 'array',
            'area.*' => 'string',
            'added_price' => 'array',
            'added_price.*' => 'string',
            'minDistance' => 'required|integer|gt:0',
            'minDays' => 'required|integer|gt:0',
            'cc' => 'required|string',
            'navigation' => 'required|string',
            'year' => 'required|digits:4|integer|min:2000|max:'.(date('Y')+0),
            'pickup' => 'required|string',
            'pickoff' => 'required|string',
            
            

        ]);

        $vehicle = new Vehicle();
        $vehicle->name = $request->name;
        $vehicle->brand_id = $request->brand;
        $vehicle->seater_id = $request->seater;
        $vehicle->type_id = $request->type;
        $vehicle->price = $request->price;
        $vehicle->details = $request->details;
        $vehicle->model = $request->model;
        $vehicle->doors = $request->doors;
        $vehicle->transmission = $request->transmission;
        $vehicle->fuel_type = $request->fuel_type;
        $vehicle->minDistance=$request->minDistance;
        $vehicle->minDays=$request->minDays;
        $vehicle->cc=$request->cc;
        $vehicle->navigation=$request->navigation;
        $vehicle->year=$request->year;
        $vehicle->pickup=$request->pickup;
        $vehicle->pickoff=$request->pickoff;
        $vehicle->picks=$request->picks;

        $vehicle->host_id = Auth::guard('host')->id();

        foreach ($request->label as $key => $item) {
            $specifications[$item] = [
                $request->icon[$key],
                $request->label[$key],
                $request->value[$key]
            ];
        }
        $vehicle->specifications = $specifications;
        
        foreach ($request->added_price as $key => $item) {
            $areas[$item] = [
                $request->area[$key],
                $request->added_price[$key],
                
            ];
        }
        $vehicle->areas = $areas;
        // Upload image
        foreach ($request->images as $image) {
            $path = imagePath()['vehicles']['path'];
            $size = imagePath()['vehicles']['size'];
            $images[] = uploadImage($image, $path, $size);
        }
        $vehicle->images = $images;

        $vehicle->save();

        $notify[] = ['success', 'Vehicle Added Successfully!'];
        return back()->withNotify($notify);
    }

    public function edit($id) {
        $vehicle = Vehicle::findOrFail($id);
        $pageTitle = 'Edit Vehicle';
        $brands = Brand::active()->orderBy('name')->get();
        $seaters = Seater::active()->orderBy('number')->get();
        $types = Type::active()->orderBy('name')->get();

        return view($this->activeTemplate.'host.vehicles.edit', compact('pageTitle', 'brands', 'seaters','types', 'vehicle'));
    }

    public function update(Request $request,$id)
    {
        $request->validate([
            'name' => 'required|string',
            'brand' => 'required|integer|gt:0',
            'seater' => 'required|integer|gt:0',
            'type' => 'required|integer|gt:0',
            'price' => 'required|numeric|gt:0',
            'details' => 'required|string',
            'model' => 'required|string',
            'doors' => 'required|integer|gt:0',
            'transmission' => 'required|string',
            'fuel_type' => 'required|string',
            'images.*' => ['required', 'max:10000', new FileTypeValidate(['jpeg','jpg','png','gif'])],
            'icon' => 'required|array',
            'icon.*' => 'required|string',
            'label' => 'required|array',
            'label.*' => 'required|string',
            'value' => 'required|array',
            'value.*' => 'required|string',
            'area' => 'array',
            'area.*' => 'string',
            'added_price' => 'array',
            'added_price.*' => 'string',
            'minDistance' => 'required|integer|gt:0',
            'minDays' => 'required|integer|gt:0',
            'cc' => 'required|string',
            'navigation' => 'required|string',
            'year' => 'required|string',
            'pickup' => 'required|string',
            'pickoff' => 'required|string',
        ]);

        $vehicle = Vehicle::findOrFail($id);
        $vehicle->name = $request->name;
        $vehicle->brand_id = $request->brand;
        $vehicle->seater_id = $request->seater;
        $vehicle->type_id = $request->type;
        $vehicle->price = $request->price;
        $vehicle->details = $request->details;
        $vehicle->model = $request->model;
        $vehicle->doors = $request->doors;
        $vehicle->transmission = $request->transmission;
        $vehicle->fuel_type = $request->fuel_type;
        $vehicle->minDistance=$request->minDistance;
        $vehicle->minDays=$request->minDays;
        $vehicle->cc=$request->cc;
        $vehicle->navigation=$request->navigation;
        $vehicle->year=$request->year;
        $vehicle->pickup=$request->pickup;
        $vehicle->pickoff=$request->pickoff;
        $vehicle->picks=$request->picks;

        foreach ($request->label as $key => $item) {
            $specifications[$item] = [
                $request->icon[$key],
                $request->label[$key],
                $request->value[$key]
            ];
        }
        $vehicle->specifications = $specifications;
        foreach ($request->added_price as $key => $item) {
            $areas[$item] = [
                $request->area[$key],
                $request->added_price[$key],
                
            ];
        }
        $vehicle->areas = $areas;

        // Upload and Update image
        if ($request->images){
            foreach ($request->images as $image) {
                $path = imagePath()['vehicles']['path'];
                $size = imagePath()['vehicles']['size'];

                $images[] = uploadImage($image, $path, $size);
            }
            $vehicle->images = array_merge($vehicle->images, $images);
        }

        $vehicle->save();

        $notify[] = ['success', 'Vehicle Updated Successfully!'];
        return redirect()->route('vehicle.details', [$id, slug($vehicle->name)])->withNotify($notify);
        return back()->withNotify($notify);
    }

    public function deleteImage($id, $image)
    {
        $vehicle = Vehicle::findOrFail($id);

        $images = $vehicle->images;
        $path = imagePath()['vehicles']['path'];

        if (($old_image = array_search($image, $images)) !== false){
            removeFile($path.'/' . $old_image);
            unset($images[$old_image]);
        }

        $vehicle->images = $images;
        $vehicle->save();

        return response()->json(['success' => true, 'message' => 'Vehicle image deleted!']);
    }

    public function status($id) {
        $vehicle = Vehicle::findOrFail($id);
        $vehicle->status = ($vehicle->status ? 0 : 1);
        $vehicle->save();

        $notify[] = ['success', ($vehicle->status ? 'Activated!' : 'Deactivated!')];
        return redirect()->route('host.vehicles.index')->withNotify($notify);
    }

}
