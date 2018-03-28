<template>
    <div>
        <!-- component template -->
        <table class="table">
            <thead>
            <tr>
                <th v-for="key in getColumns"
                    @click="sortBy(key)"
                    :class="[{'indicator-col': key === 'indicator'}, { active: sortKey === key }, {'has-text-centered': key === 'action'}, 'grid-header']">
                    <span v-show="key !== 'indicator'">{{ key.toLowerCase() }}</span>
                    <span class="arrow" :class="sortOrders[key] > 0 ? 'asc' : 'dsc'">
            </span>
                </th>
            </tr>
            </thead>
            <tbody>
            <tr v-for="entry in filteredData" v-show="entry.show" :key="entry['device_id']">
                <td v-for="(key, index) in columns" :class="[{'indicator-col': key === 'indicator'}, {'has-text-centered': key === 'action'}]">
                    <div v-if="key === 'indicator'">
                        <chevron-double-right class="indicator"></chevron-double-right>
                    </div>

                    <div v-else-if="key !== 'action'">
                        {{entry[key]}}
                    </div>

                    <div v-else-if="key === 'action'">
                        <div v-if="entry['lendee_id'] !== null">
                            <button class="button is-warning"
                                    @click="deviceModal(entry['device_id'], 'return')">
                                Return
                                <subdirectory-arrow-left class="push-left"></subdirectory-arrow-left>
                            </button>
                        </div>

                        <div v-else>
                            <button class="button is-danger"
                                    @click="deviceModal(entry['device_id'], 'remove')">
                                Remove
                                <delete-forever class="push-left"></delete-forever>
                            </button>
                        </div>
                    </div>
                </td>
            </tr>
            </tbody>
        </table>

        <div class="modal" :class="{'is-active': showDeviceModal}">
            <div class="modal-background"></div>
            <div class="modal-card">
                <header class="modal-card-head">
                    <p class="modal-card-title" v-text="modalTitle"></p>
                    <button class="delete" aria-label="close" @click="showDeviceModal = false"></button>
                </header>
                <section class="modal-card-body">
                    Are you sure you want to <strong>{{modalTitle}}</strong>?
                </section>
                <footer class="modal-card-foot">
                    <button class="button is-success" @click="returnDevice">Yes</button>
                    <button class="button" @click="showDeviceModal = false">Cancel</button>
                </footer>
            </div>
        </div>
    </div>
</template>

<script>
    import KeyboardReturn from 'vue-material-design-icons/keyboard-return';
    import DeleteForever from 'vue-material-design-icons/delete-forever';
    import SubdirectoryArrowLeft from 'vue-material-design-icons/subdirectory-arrow-left';
    import ChevronDoubleRight from 'vue-material-design-icons/chevron-double-right';


    export default {
    name: 'grid',

    components: {
      ChevronDoubleRight,
      KeyboardReturn,
      DeleteForever,
      SubdirectoryArrowLeft,
    },

    props: {
      data: Array,
      columns: Array,
      filterKey: String
    },

    data() {
      const sortOrders = {};

      this.columns.forEach(function (key) {
        sortOrders[key] = 1;
      });

      return {
        sortKey: '',
        sortOrders: sortOrders,
        showDeviceModal: false,
        actionDeviceId: -1,
        modalTitle: '',
        actionUrl: ''
      };
    },

    computed: {
      getColumns() {
        this.columns.push('action');
        this.columns.unshift('indicator');

        return this.columns;
      },

      filteredData: function () {
        const sortKey = this.sortKey;
        const filterKey = this.filterKey && this.filterKey.toLowerCase();
        const order = this.sortOrders[sortKey] || 1;
        let data = this.data;

        if (filterKey) {
          data = data.filter(function (row) {
            return Object.keys(row).some(function (key) {
              return String(row[key]).toLowerCase().indexOf(filterKey) > -1;
            });
          });
        }

        if (sortKey) {
          data = data.slice().sort(function (a, b) {
            a = a[sortKey];
            b = b[sortKey];
            return (a === b ? 0 : a > b ? 1 : -1) * order;
          });
        }

        return data;
      }
    },

    filters: {
      capitalize: function (str) {
        str = str.replace('_id', '').replace('_', ' ');
        return str.charAt(0).toUpperCase() + str.slice(1);
      }
    },

    methods: {
      actionKey(entry) {
        return entry['device_id'] + '_action_column';
      },

      sortBy: function (key) {
        this.sortKey = key;
        this.sortOrders[key] = this.sortOrders[key] * -1;
      },

      deviceModal(deviceId, mode) {
        if (mode === 'return') {
          this.modalTitle = 'Return Device';
          this.actionUrl = `/api/assign/${deviceId}`;
        } else {
          this.modalTitle = 'Remove Device';
          this.actionUrl = `/api/device/${deviceId}`;
        }

        this.showDeviceModal = true;
      },

      returnDevice() {
        axios.delete(this.actionUrl);
        this.showDeviceModal = false;
        this.modalTitle = '';
        this.actionUrl = '';
      },
    }
  };
</script>

<style lang="scss" scoped>
    @import '../../sass/initial-variables';

    table {
        width: 100%
    }

    table th.grid-header {
        text-transform: capitalize;
    }

    table tr:hover :not(th) {
        .indicator {
            display: block;
        }
    }
    .indicator {
        display: none;
    }
    table td.indicator-col,table th.indicator-col {
        width: 3em;
        border: none !important;
    }

    .push-left {
        padding-left: 0.5em;
    }

    button.button {
        z-index: 10;
    }
</style>