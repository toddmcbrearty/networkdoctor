@extends('layouts.app')

@section('content')
    <section class="section">
        <div class="">
            <ticker :devices="devices" @device-selected="deviceSelected"></ticker>
        </div>

        <div class="is-divider" data-content="click above to sort"></div>

        <div class="container">
            <device-display :devices="devices" :selected="selectedDevice" @assigner-show="toggleDeviceAssigner"></device-display>
        </div>

        <devices @devices-updated="setDevices" :refresh="refresh"></devices>
        <device-assigner :is-active="deviceAssignerActive" @data-update="refreshList"></device-assigner>
    </section>
@endsection
