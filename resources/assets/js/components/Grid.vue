<template>
  <!-- component template -->
    <table class="table">
      <thead>
        <tr>
          <th v-for="key in columns"
            @click="sortBy(key)"
            :class="{ active: sortKey == key }">
            {{ key | capitalize }}
            <span class="arrow" :class="sortOrders[key] > 0 ? 'asc' : 'dsc'">
            </span>
          </th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="entry in filteredData" v-show="entry.show">
          <td v-for="key in columns">
            {{entry[key]}}
          </td>
        </tr>
      </tbody>
    </table>
</template>

<script>
  export default {
    name: 'grid',

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
        sortOrders: sortOrders
      };
    },

    computed: {
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
      sortBy: function (key) {
        this.sortKey = key;
        this.sortOrders[key] = this.sortOrders[key] * -1;
      }
    }
  };
</script>

<style scoped>
table {
  width: 100%
}
</style>