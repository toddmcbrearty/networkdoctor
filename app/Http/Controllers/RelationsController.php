<?php

namespace App\Http\Controllers;

use App\Relation;
use Illuminate\Http\Request;

class RelationsController extends Controller
{
    public function index()
    {
        return response()->json(Relation::get());
    }
}
