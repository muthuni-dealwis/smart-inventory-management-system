<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Locker;
use App\Models\Order;
use Illuminate\Http\Request;

class LockerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $lockers = Locker::paginate(16);
        //dd($lockers);
        return view('backend.locker.details.index', compact('lockers'));
        // return "Hi";
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $orders = Order::pluck('id');
        $Locker_id = Locker::getNextLockerId();
        return view('backend.locker.details.create', compact('orders','Locker_id'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = request()->validate([
            // 'order_id' => 'numeric|nullable',

            'notes' => 'string|nullable',

            'is_available' => 'nullable|boolean',
        ]);
        
        try {

            $locker = new Locker($data);

            // Update checkbox condition
            $locker->is_available = $request->input('is_available') ? true : false;
            $locker->order_id = NULL; 

            $locker->save();
            return redirect()->route('admin.locker.details.index')->with('Success', 'locker was created !');

        } catch (\Exception $ex) {
            return abort(500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  Locker $lockerDetail
     * @return \Illuminate\Http\Response
     */
    public function show(Locker $lockerDetail)
    {
        return view('backend.locker.details.show', compact("lockerDetail"));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Locker $lockerDetail)
    {
        //dd($lockerDetail);
        $orders = Order::pluck('id');
        return view('backend.locker.details.edit', compact('orders','lockerDetail'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Locker $lockerDetail)
    {
        $data = request()->validate([
            'order_id' => 'numeric|nullable',

            'notes' => 'string|nullable',

            'is_available' => 'nullable|boolean',
        ]);
        try {

            // Update checkbox condition
            $lockerDetail->is_available = $request->input('is_available') ? true : false;

            $lockerDetail->update($data);
            return redirect()->route('admin.locker.details.index')->with('Success', 'locker was updated !');

        } catch (\Exception $ex) {
            return abort(500);
        }
    }

    /**
     * Confirm to delete the specified resource from storage.
     *
     */
    public function delete(Locker $lockerDetail)
    {
        return view('backend.locker.details.delete', compact('lockerDetail'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Locker $lockerDetail)
    {
        try {

            $lockerDetail->delete();
            return redirect()->route('admin.locker.details.index')->with('Success', 'locker was deleted !');

        } catch (\Exception $ex) {
            return abort(500);
        }
    }

    public function index_for_ready_orders()
    {

        $orders = Order::getReadyOrders();
        //dd($orders);

        return view('backend.locker.ready_orders.index', compact('orders'));
    }
}