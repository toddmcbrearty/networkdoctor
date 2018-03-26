<template>
    <div>
        <nav class="level">
            <!-- Left side -->
            <div class="level-left">
                <div class="level-item">
                    <div class="field has-addons">
                        <p class="control">
                            <input class="input" type="text" placeholder="Search devices" v-model="searchQuery">
                        </p>
                    </div>
                </div>
            </div>

            <!-- Right side -->
            <div class="level-right sort-items">
                <a href="#" @click="show('all')"
                   :class="[{'is-active': activeSortItem === 'all'}, 'tag', 'level-item']">All</a>

                <a href="#" @click="show('out')"
                   :class="[{'is-active': activeSortItem === 'out'}, 'tag', 'level-item']">Lent Out</a>

                <a href="#" @click="show('available')"
                   :class="[{'is-active': activeSortItem === 'available'}, 'tag', 'level-item']">Available</a>
            </div>
        </nav>
        <div class="container">
            <grid :data="devices"
                  :columns="['name', 'device_id', 'lendee_name']"
                  :filter-key="searchQuery"
            ></grid>
        </div>
    </div>
</template>

<script>
  import Magnify from 'vue-material-design-icons/magnify';
  import Grid from './Grid';

  export default {
    name: 'device-display',

    components: {
      Magnify,
      Grid
    },

    props: {
      devices: {
        type: Array
      },
      selected: {
        type: Number
      }
    },
    watch: {
      devices: function (devices) {
        this.allDevices = devices;
      },
      selected: function (selected) {
        this.currentDevice = this.allDevices[--selected];
      }
    },

    data() {
      return {
        currentDevice: {},
        allDevices: [],
        activeSortItem: 'all',
        searchQuery: '',
      };
    },

    methods: {
      show(showType) {
        let showValue;
        this.allDevices.forEach(device => {
          switch (showType) {
            case 'all':
              showValue = true;
              break;

            case 'out':
              showValue = !!device.lendee_name;
              break;

            case 'available':
              showValue = !device.lendee_name;
              break;
          }

          this.activeSortItem = showType;

          device.show = showValue;
        });
      }
    },
  };
</script>

<style lang="scss" scoped>
    @import '../../sass/initial-variables.sass';

    .tag {
        font-size: 1em;
        pointer: cursor;
    }

    a.is-active {
        text-decoration: none;
        font-weight: 700;
        background: $yellow;
    }
</style>