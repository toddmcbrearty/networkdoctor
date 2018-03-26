<?php

namespace App\Http\Controllers;

use App\Device;
use DB;
use Illuminate\Http\Request;

class DevicesController extends Controller
{
    public function ticker() {
        $selectFuncs = DB::raw('ANY_VALUE(id) AS id, COUNT(id) AS total, sum(IF(IFNULL(lendee_id, FALSE), 1, 0)) AS on_loan');
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
}
