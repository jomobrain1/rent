<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Host;
use App\Models\RentLog;
use App\Models\Seater;
use App\Models\Type;
use App\Models\Vehicle;
use App\Rules\FileTypeValidate;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class VehicleController extends Controller
{
    public function index($id = '')
    {
        if($id!= ''){
            $vehicles = Vehicle::with(['brand', 'seater', 'type'])->where('host_id', $id)->latest()->paginate(getPaginate());
        } else {
            $vehicles = Vehicle::with(['brand', 'seater', 'type'])->latest()->paginate(getPaginate());
        }
        $pageTitle = 'Vehicles';
        $empty_message = 'No vehicle has been added.';
        return view('admin.vehicle.index', compact('pageTitle', 'empty_message', 'vehicles'));
    }

    public function add()
    {
        $pageTitle = 'Add vehicle';
        $brands = Brand::active()->orderBy('name')->get();
        $seaters = Seater::active()->orderBy('number')->get();
        $types = Type::active()->orderBy('name')->get();
        $hosts = Host::where('status', 1)->get();
        return view('admin.vehicle.add', compact('pageTitle', 'brands', 'seaters', 'types', 'hosts'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'brand' => 'required|integer|gt:0',
            'seater' => 'required|integer|gt:0',
            'type' => 'required|integer|gt:0',
            'price' => 'required|numeric|gt:0',
            'host_id' => 'required|integer|gt:0',
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
        $vehicle->host_id = $request->host_id;

        foreach ($request->label as $key => $item) {
            $specifications[$item] = [
                $request->icon[$key],
                $request->label[$key],
                $request->value[$key]
            ];
        }
        $vehicle->specifications = $specifications;

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

    public function edit($id)
    {
        $vehicle = Vehicle::findOrFail($id);
        $pageTitle = 'Edit Vehicle';
        $brands = Brand::active()->orderBy('name')->get();
        $seaters = Seater::active()->orderBy('number')->get();
        $types = Type::active()->orderBy('name')->get();
        $hosts = Host::where('status', 1)->get();
        return view('admin.vehicle.edit', compact('pageTitle', 'brands', 'seaters', 'types', 'vehicle', 'hosts'));
    }

    public function update(Request $request,$id)
    {
        $request->validate([
            'name' => 'required|string',
            'brand' => 'required|integer|gt:0',
            'seater' => 'required|integer|gt:0',
            'type' => 'required|integer|gt:0',
            'host_id' => 'required|integer|gt:0',
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
        $vehicle->host_id = $request->host_id;

        foreach ($request->label as $key => $item) {
            $specifications[$item] = [
                $request->icon[$key],
                $request->label[$key],
                $request->value[$key]
            ];
        }
        $vehicle->specifications = $specifications;

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

    public function status($id)
    {
        $vehicle = Vehicle::findOrFail($id);
        $vehicle->status = ($vehicle->status ? 0 : 1);
        $vehicle->save();

        $notify[] = ['success', ($vehicle->status ? 'Activated!' : 'Deactivated!')];
        return back()->withNotify($notify);
    }

    //Booking Log
    public function bookingLog()
    {
        $booking_logs = RentLog::active()->with(['vehicle', 'user', 'pick_up_location', 'drop_up_location'])->latest()->paginate(getPaginate());
        $pageTitle = 'Vehicle Booking Log';
        $empty_message = 'No data found.';
        return view('admin.vehicle.bookinglog', compact('pageTitle', 'empty_message', 'booking_logs'));
    }
    public function upcomingBookingLog()
    {
        $booking_logs = RentLog::active()->upcoming()->with(['vehicle', 'user', 'pick_up_location', 'drop_up_location'])->latest()->paginate(getPaginate());
        $pageTitle = 'Vehicle Upcoming Booking Log';
        $empty_message = 'No data found.';
        return view('admin.vehicle.bookinglog', compact('pageTitle', 'empty_message', 'booking_logs'));
    }
    public function runningBookingLog()
    {
        $booking_logs = RentLog::active()->running()->with(['vehicle', 'user', 'pick_up_location', 'drop_up_location'])->latest()->paginate(getPaginate());
        $pageTitle = 'Vehicle Running Booking Log';
        $empty_message = 'No data found.';
        return view('admin.vehicle.bookinglog', compact('pageTitle', 'empty_message', 'booking_logs'));
    }
    public function completedBookingLog()
    {
        $booking_logs = RentLog::active()->completed()->with(['vehicle', 'user', 'pick_up_location', 'drop_up_location'])->latest()->paginate(getPaginate());
        $pageTitle = 'Vehicle Completed Booking Log';
        $empty_message = 'No data found.';
        return view('admin.vehicle.bookinglog', compact('pageTitle', 'empty_message', 'booking_logs'));
    }

    public function userBookingLog($id)
    {
        $booking_logs = RentLog::active()->where('user_id', $id)->with(['vehicle', 'user', 'pick_up_location', 'drop_up_location'])->latest()->paginate(getPaginate());
        $pageTitle = 'User Vehicle Booking Log';
        $empty_message = 'No data found.';
        return view('admin.vehicle.bookinglog', compact('pageTitle', 'empty_message', 'booking_logs'));
    }
    public function userUpcomingBookingLog($id)
    {
        $booking_logs = RentLog::active()->where('user_id', $id)->upcoming()->with(['vehicle', 'user', 'pick_up_location', 'drop_up_location'])->latest()->paginate(getPaginate());
        $pageTitle = 'User Vehicle Upcoming Booking Log';
        $empty_message = 'No data found.';
        return view('admin.vehicle.bookinglog', compact('pageTitle', 'empty_message', 'booking_logs'));
    }
    public function userRunningBookingLog($id)
    {
        $booking_logs = RentLog::active()->where('user_id', $id)->running()->with(['vehicle', 'user', 'pick_up_location', 'drop_up_location'])->latest()->paginate(getPaginate());
        $pageTitle = 'User Vehicle Running Booking Log';
        $empty_message = 'No data found.';
        return view('admin.vehicle.bookinglog', compact('pageTitle', 'empty_message', 'booking_logs'));
    }
    public function userCompletedBookingLog($id)
    {
        $booking_logs = RentLog::active()->where('user_id', $id)->completed()->with(['vehicle', 'user', 'pick_up_location', 'drop_up_location'])->latest()->paginate(getPaginate());
        $pageTitle = 'User Vehicle Completed Booking Log';
        $empty_message = 'No data found.';
        return view('admin.vehicle.bookinglog', compact('pageTitle', 'empty_message', 'booking_logs'));
    }

    public function hostBookingLog($id)
    {
        $booking_logs = RentLog::active()->where('host_id', $id)->with(['vehicle', 'user', 'pick_up_location', 'drop_up_location'])->latest()->paginate(getPaginate());
        $pageTitle = 'Owner Vehicle Booking Log';
        $empty_message = 'No data found.';
        return view('admin.vehicle.bookinglog', compact('pageTitle', 'empty_message', 'booking_logs'));
    }
    public function hostUpcomingBookingLog($id)
    {
        $booking_logs = RentLog::active()->where('host_id', $id)->upcoming()->with(['vehicle', 'user', 'pick_up_location', 'drop_up_location'])->latest()->paginate(getPaginate());
        $pageTitle = 'Owner Vehicle Upcoming Booking Log';
        $empty_message = 'No data found.';
        return view('admin.vehicle.bookinglog', compact('pageTitle', 'empty_message', 'booking_logs'));
    }
    public function hostRunningBookingLog($id)
    {
        $booking_logs = RentLog::active()->where('host_id', $id)->running()->with(['vehicle', 'user', 'pick_up_location', 'drop_up_location'])->latest()->paginate(getPaginate());
        $pageTitle = 'Owner Vehicle Running Booking Log';
        $empty_message = 'No data found.';
        return view('admin.vehicle.bookinglog', compact('pageTitle', 'empty_message', 'booking_logs'));
    }
    public function hostCompletedBookingLog($id)
    {
        $booking_logs = RentLog::active()->where('host_id', $id)->completed()->with(['vehicle', 'user', 'pick_up_location', 'drop_up_location'])->latest()->paginate(getPaginate());
        $pageTitle = 'Owner Vehicle Completed Booking Log';
        $empty_message = 'No data found.';
        return view('admin.vehicle.bookinglog', compact('pageTitle', 'empty_message', 'booking_logs'));
    }
}
