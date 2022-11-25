<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Plan;
use App\Models\PlanLog;
use Illuminate\Http\Request;

class PlanController extends Controller
{
    public function index()
    {
        $plans = Plan::latest()->paginate(getPaginate());
        $pageTitle = 'Plans';
        $empty_message = 'No plan has been added.';
        return view('admin.plan.index', compact('pageTitle', 'empty_message', 'plans'));
    }

    public function add()
    {
        $pageTitle = 'Add plan';
        return view('admin.plan.add', compact('pageTitle'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'price' => 'required|numeric|gt:0',
            'days' => 'required|integer|gt:0',
            'included' => 'required|array',
            'included.*' => 'required|string',
            'excluded' => 'nullable|array',
            'excluded.*' => 'required|string',
        ]);

        $plan = new plan();
        $plan->name = $request->name;
        $plan->price = $request->price;
        $plan->days = $request->days;
        $plan->included = $request->included;
        $plan->excluded = $request->excluded;
        $plan->save();

        $notify[] = ['success', 'Plan Added Successfully!'];
        return back()->withNotify($notify);
    }

    public function edit($id)
    {
        $plan = Plan::findOrFail($id);
        $pageTitle = 'Edit plan';
        return view('admin.plan.edit', compact('pageTitle', 'plan'));
    }

    public function update(Request $request,$id)
    {
        $request->validate([
            'name' => 'required|string',
            'price' => 'required|numeric|gt:0',
            'days' => 'required|integer|gt:0',
            'included' => 'required|array',
            'included.*' => 'required|string',
            'excluded' => 'required|array',
            'excluded.*' => 'required|string',
        ]);

        $plan = plan::findOrFail($id);
        $plan->name = $request->name;
        $plan->price = $request->price;
        $plan->days = $request->days;
        $plan->included = $request->included;
        $plan->excluded = $request->excluded;
        $plan->save();

        $notify[] = ['success', 'Plan Updated Successfully!'];
        return back()->withNotify($notify);
    }

    public function status($id)
    {
        $plan = plan::findOrFail($id);
        $plan->status = ($plan->status ? 0 : 1);
        $plan->save();

        $notify[] = ['success', ($plan->status ? 'Activated!' : 'Deactivated!')];
        return back()->withNotify($notify);
    }

    //Booking Log
    public function bookingLog()
    {
        $plan_logs = PlanLog::active()->with(['plan', 'user', 'pick_up_location'])->latest()->paginate(getPaginate());
        $pageTitle = 'Plan Booking Log';
        $empty_message = 'No data found.';
        return view('admin.plan.bookinglog', compact('pageTitle', 'empty_message', 'plan_logs'));
    }
    public function upcomingBookingLog()
    {
        $plan_logs = PlanLog::active()->upcoming()->with(['plan', 'user', 'pick_up_location'])->latest()->paginate(getPaginate());
        $pageTitle = 'Plan Upcoming Booking Log';
        $empty_message = 'No data found.';
        return view('admin.plan.bookinglog', compact('pageTitle', 'empty_message', 'plan_logs'));
    }
    public function runningBookingLog()
    {
        $plan_logs = PlanLog::active()->running()->with(['plan', 'user', 'pick_up_location'])->latest()->paginate(getPaginate());
        $pageTitle = 'Plan Running Booking Log';
        $empty_message = 'No data found.';
        return view('admin.plan.bookinglog', compact('pageTitle', 'empty_message', 'plan_logs'));
    }
    public function completedBookingLog()
    {
        $plan_logs = PlanLog::active()->completed()->with(['plan', 'user', 'pick_up_location'])->latest()->paginate(getPaginate());
        $pageTitle = 'Plan Completed Booking Log';
        $empty_message = 'No data found.';
        return view('admin.plan.bookinglog', compact('pageTitle', 'empty_message', 'plan_logs'));
    }

    public function userBookingLog($id)
    {
        $plan_logs = PlanLog::active()->where('user_id', $id)->with(['plan', 'user', 'pick_up_location'])->latest()->paginate(getPaginate());
        $pageTitle = 'User Plan Booking Log';
        $empty_message = 'No data found.';
        return view('admin.plan.bookinglog', compact('pageTitle', 'empty_message', 'plan_logs'));
    }
    public function userUpcomingBookingLog($id)
    {
        $plan_logs = PlanLog::active()->where('user_id', $id)->upcoming()->with(['plan', 'user', 'pick_up_location'])->latest()->paginate(getPaginate());
        $pageTitle = 'User Plan Upcoming Booking Log';
        $empty_message = 'No data found.';
        return view('admin.plan.bookinglog', compact('pageTitle', 'empty_message', 'plan_logs'));
    }
    public function userRunningBookingLog($id)
    {
        $plan_logs = PlanLog::active()->where('user_id', $id)->running()->with(['plan', 'user', 'pick_up_location'])->latest()->paginate(getPaginate());
        $pageTitle = 'User Plan Running Booking Log';
        $empty_message = 'No data found.';
        return view('admin.plan.bookinglog', compact('pageTitle', 'empty_message', 'plan_logs'));
    }
    public function userCompletedBookingLog($id)
    {
        $plan_logs = PlanLog::active()->where('user_id', $id)->completed()->with(['plan', 'user', 'pick_up_location'])->latest()->paginate(getPaginate());
        $pageTitle = 'User Plan Completed Booking Log';
        $empty_message = 'No data found.';
        return view('admin.plan.bookinglog', compact('pageTitle', 'empty_message', 'plan_logs'));
    }
}
