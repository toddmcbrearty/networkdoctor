<?php

namespace App\Http\Controllers;

use App\Device;
use App\DeviceType;
use App\Lendee;
use App\LendeeRelation;
use Illuminate\Http\Request;

class AssignerController extends Controller
{
    /**
     * @var Device
     */
    protected $device;
    /**
     * @var DeviceType
     */
    protected $deviceType;
    /**
     * @var LendeeRelation
     */
    protected $relation;
    /**
     * @var Lendee
     */
    protected $lendee;

    /**
     * AssignerController constructor.
     * @param Device $device
     * @param DeviceType $deviceType
     * @param LendeeRelation $relation
     * @param Lendee $lendee
     */
    public function __construct(Device $device, DeviceType $deviceType, LendeeRelation $relation, Lendee $lendee)
    {
        $this->device = $device;
        $this->deviceType = $deviceType;
        $this->relation = $relation;
        $this->lendee = $lendee;
    }

    public function index()
    {
        $response = [
            'device_types' => $this->deviceType->select('id', 'name')->get(),
            'relations' => $this->relation->select('id', 'name')->get(),
            'devices' => $this->device->select('id', 'name', 'device_id')->whereNull('lendee_id')->get(),
            'lendees' => $this->lendee->select('id', 'name', 'relation')->get(),
        ];

        return response()->json($response);
    }

    public function store(Request $request)
    {
        $data = $request->json()->all();

        //if lendee is a string we'll need to create them first
        if (is_int($data['lendee'])) {
            $this->lendee = $this->lendee->find($data['lendee']);

            //assign relation to lendee
            if ($this->lendee->relation !== $data['relation']) {
                $this->lendee->relation = $data['relation'];
                $this->lendee->save();
            }
        } else {
            $this->lendee->name = $data['lendee'];
            $this->lendee->relation = $data['relation'];
            $this->lendee->save();
        }

        //assign lendee to device
        $saved = false;
        $message = '';
        if ($this->lendee) {
            $this->device = $this->device->whereDeviceId($data['device_id'])->first();
            if ($this->device->lendee_id) {
                $message = 'this device is already assigned';
            } else {
                $this->device->lendee_id = $this->lendee->id;
                $saved = $this->device->save();
            }
        }

        return response()->json(['saved' => $saved, 'message' => $message]);
    }

    public function destroy($deviceId)
    {
        $device = $this->device->whereDeviceId($deviceId)->first();

        if(!$device) {
            return response()->json(['unassigned' => false, 'message' => 'Device not found.']);
        }

        $device->lendee_id = null;
        $device->save();

        return response()->json(['unassigned' => true]);
    }
}
