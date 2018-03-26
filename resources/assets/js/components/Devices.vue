<template></template>
<script>
  export default {
    name: 'devices',

    props: ['refresh'],
    watch: {
      refresh(refresh) {
        if (refresh) this.getData();
      }
    },

    data() {
      return {
        items: {}
      };
    },

    created() {
      this.getData();
    },

    methods: {
      getData() {
        axios.get('/api/devices')
          .then(({data}) => {
            this.items = data;
            this.$emit('devices-updated', this.items);
          })
          .catch(e => {
            console.log(e.getMessage());
          });
      },
    }
  };
</script>
