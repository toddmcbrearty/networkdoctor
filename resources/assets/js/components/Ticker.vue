<template>
    <div>
        <nav class="columns">
            <div class="column has-text-centered" v-for="device in deviceList" :key="device.id">
                <a href="#" class="selectable-device" @click="selectDevice(device.id)">
                    <div class="box item">
                        <p class="heading is-size-4" v-text="device.name"></p>
                        <p class="title" v-text="deviceCount(device)"></p>
                    </div>
                </a>
            </div>
        </nav>
    </div>
</template>

<script>
    export default {
        name: "ticker",

        data() {
            return {
                deviceList: {},
                device: -1,
            }
        },

        created() {
            this.getData()
        },

        methods: {
            selectDevice: function (deviceId) {
                this.selectedDevice = deviceId;
                this.$emit('device-selected', deviceId);
            },

            getData() {
                axios.get('/api/ticker')
                    .then(({data}) => {
                        this.deviceList = data;
                    })
                    .catch(e => {
                        console.log(e.getMessage());
                    })
            },

            deviceCount(device) {
                return device.on_loan + '/' + device.total
            }
        },
    }
</script>

<style lang="scss">
    .box {
        border-color: #5b7730 !important;
        cursor: pointer
    }

    .box:hover {
        box-shadow: 0 1px 3px;
    }

    .selectable-device {
        display: block;
    }
</style>