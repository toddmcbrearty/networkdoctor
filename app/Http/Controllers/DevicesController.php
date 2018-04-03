<?php

namespace App\Http\Controllers;

use App\Device;
use DB;
use Illuminate\Http\Request;

class DevicesController extends Controller
{
    public function ticker() {
        $selectFuncs = DB::raw('min(id) AS id, COUNT(id) AS total, sum(IF(IFNULL(lendee_id, FALSE), 1, 0)) AS on_loan');
       
        $ticker = Device::select($selectFuncs, 'name')->groupBy('name')->get();

        return response()->json($ticker);
    }

    public function devices()
    {
        $devices = Device::with('lendee')->get();
        $devices->makeHidden(['lendee', 'device_type']);

        $devices->each(function($device) {
            $device->lendee_name = $device->lendee ? $device->lendee->name : null;
            $device->show = true;
        });

        return response()->json($devices);
    }

    public function destroy(Device $device, $id) {
        $device = $device->whereDeviceId($id)->first();

        if(!$device) {
            $deleted = false;
        } else {
            $deleted = $device->delete();
        }

        return response()->json(['delete' => $deleted]);
    }
}
