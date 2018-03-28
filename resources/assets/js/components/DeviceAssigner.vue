<template>
    <div class="modal" :class="{'is-active': this.activeState}" @keyup.esc="activeState = false">
        <div class="modal-background"></div>
        <div class="modal-card">
            <header class="modal-card-head">
                <p class="modal-card-title">Assign Device to Person</p>
                <button class="delete" aria-label="close" @click="activeState = false"></button>
            </header>
            <section class="modal-card-body">
                <div v-show="isAssigned">
                    <progress class="progress" :value="assigningProgress" max="100">{{assigningProgress}}%</progress>
                </div>
                <div v-show="!isAssigned">
                    <div class="columns">
                        <div class="field column">
                            <label class="label">Device Type</label>
                            <div class="control">
                                <div class="select">
                                    <select v-model="assigned.deviceType">
                                        <option value="-1">Select Device Type</option>
                                        <option v-for="type in deviceTypes" :value="type.id"
                                                v-text="type.name"></option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="field column">
                            <label class="label">Device ID</label>
                            <div class="control is-extended">
                                <div class="select is-fullwidth">
                                    <select v-model="assigned.device" :disabled="!deviceSelected">
                                        <option v-for="device in selectedDevices"
                                                v-text="device.device_id"
                                                :value="device.device_id"
                                                :key="device.device_id"></option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="field">
                        <label class="label newline-after-label">Lendees</label>
                        <lendee-names :lendees="lendees"
                                      @lendee-name-selected="setLendeeId"
                                      @lendee-name-added="addLendeeName"
                        ></lendee-names>
                    </div>
                    <div class="field">
                        <label class="label">Relation</label>
                        <div class="control">
                            <div class="select">
                                <select v-model="assigned.relation">
                                    <option value="-1">Select relation</option>
                                    <option v-for="relation in relations" :value="relation.id"
                                            v-text="relation.name"></option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="field">
                        <div class="steps">
                            <div class="step-item" :class="{'is-active is-success': assigned.device !== -1}">
                                <div class="step-marker">1</div>
                                <div class="step-details">
                                    <p class="step-title">Device</p>
                                </div>
                            </div>
                            <div class="step-item" :class="{'is-active is-success': assigned.lendeeName !== ''}">
                                <div class="step-marker">2</div>
                                <div class="step-details">
                                    <p class="step-title">Lendee</p>
                                </div>
                            </div>
                            <div class="step-item" :class="{'is-active is-success': assigned.relation !== -1}">
                                <div class="step-marker">2</div>
                                <div class="step-details">
                                    <p class="step-title">Relation</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <footer class="modal-card-foot" v-show="!isAssigned">
                <button class="button is-danger" @click="activeState = false">Cancel</button>
                <button class="button is-success" :disabled="assignIsDisabled" @click="assignDevice">
                    Assign
                </button>
                <label class="checkbox">
                    <input type="checkbox" v-model="continueAdding">
                    continue adding more
                </label>
            </footer>
        </div>
    </div>
</template>

<script>
  import LandeeNames from './LendeeNames';

  export default {
    name: 'device-assigner',

    components: {
      LandeeNames,
    },

    props: ['is-active', 'filter-device'],
    watch: {
      isActive(isActive) {
        this.activeState = isActive;
      },
      activeState(state) {
        if (!state) this.reset();
      },
    },

    data() {
      return {
        //default data
        activeState: false,
        deviceTypes: [],
        relations: [],
        lendees: [],
        devices: [],
        continueAdding: false,
        isAssigned: false,
        assigningProgress: 1,

        //crumb data
        assigned: {
          deviceType: -1,
          device: -1,
          relation: -1,
          lendeeName: '',
        }
      };
    },

    created() {
      this.getDeviceTypes();
      this.activeState = this.isActive;
    },

    methods: {
      getDeviceTypes() {
        axios.get('/api/device/assigner/data')
          .then(({data}) => {
            this.deviceTypes = data.device_types;
            this.relations = data.relations;
            this.devices = data.devices;
            this.lendees = data.lendees;
          })
          .catch(e => {
            console.log(e.error);
          });
      },

      assignDevice() {
        axios.put('/api/device/assign', {
          device_type: this.assigned.deviceType,
          device_id: this.assigned.device,
          relation: this.assigned.relation,
          lendee: this.assigned.lendeeName,
        }).then(({data}) => {
          if (data.saved && !this.continueAdding) {
              this.isAssigned = true;

              let assignInterval = setInterval(() => {
                if (this.assigningProgress >= 100) {
                  this.reset();
                  this.activeState = false;
                  this.$emit('data-update', true);
                    assignInterval = false;
                }


                this.assigningProgress = this.assigningProgress*1.8;
              }, 25);
          }
        }).catch(e => {
          console.log(e);
        });
      },

      reset() {
        this.assigned.deviceType = -1;
        this.assigned.device = -1;
        this.assigned.relation = -1;
        this.assigned.lendeeName = '';
        this.assigningProgress = 0;
        this.isAssigned = false;
      },

      setLendeeId(lendeeId) {
        this.assigned.lendeeName = lendeeId;
      },

      addLendeeName(lendeeName) {
        this.assigned.lendeeName = lendeeName;
      },
    },

    computed: {
      deviceSelected() {
        return this.assigned.deviceType !== -1;
      },

      assignIsDisabled() {
        return this.assigned.deviceType === -1 ||
          this.assigned.device === -1 ||
          this.assigned.relation === -1 ||
          this.assigned.lendeeName === '';
      },

      selectedDevices() {
        if (this.assigned.deviceType === -1) return [];

        const selectedItem = this.deviceTypes.filter(deviceType => {
          return deviceType.id === this.assigned.deviceType;
        });

        return this.devices.filter(device => {
          return device.deviceType === selectedItem.name;
        });
      }
    }
  };
</script>

<style scoped lang="scss">

</style>