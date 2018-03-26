<?php

namespace App\Http\Controllers;

use App\DeviceType;
use Illuminate\Http\Request;

class DeviceTypesController extends Controller
{
    public function index() {
        return response()->json(DeviceType::get());
    }
}
