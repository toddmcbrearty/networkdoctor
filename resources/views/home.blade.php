@extends('layouts.app')

@section('content')
    <section class="section">
        <div class="">
            <ticker :devices="devices" @device-selected="deviceSelected"></ticker>
        </div>

        <div class="is-divider" data-content=""></div>

        <div class="section">
            <a class="button is-success" @click="toggleDeviceAssigner">Assign Device</a>
            <a class="button is-info">Add New Device</a>
            <div class="box">
                <device-display :devices="devices" :selected="selectedDevice"></device-display>
            </div>
        </div>

        <devices @devices-updated="setDevices"></devices>
        <device-assigner :is-active="deviceAssignerActive" @data-update="refreshList"></device-assigner>
    </section>
@endsection
