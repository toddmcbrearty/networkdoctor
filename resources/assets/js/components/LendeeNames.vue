<template>
    <div class="field has-addons">
        <div class="control is-expanded">
            <div class="select is-fullwidth">
                <select v-model="lendeeSelected">
                    <option value="-1">Select lendee</option>
                    <option v-for="lendee in lendeesData" :value="lendee.id"
                            v-text="lendee.name"></option>
                </select>
            </div>
        </div>
        <div class="divider" data-content="or add lendee"></div>
        <div class="control">
            <input class="input" type="text" placeholder="Landee Name" v-model="lendeeName">
        </div>
        <div class="control">
            <button class="button is-primary" title="Add new lendee" @click="addLendee">
                <account-plus title="Add new lendee"></account-plus>
            </button>
        </div>
    </div>
</template>

<script>
  import AccountPlus from 'vue-material-design-icons/account-plus';

  export default {
    name: 'lendee-names',

    components: {
      AccountPlus,
    },

    props: ['lendees'],

    watch: {
      lendeeSelected(lendeeId) {
        this.$emit('lendee-name-selected', lendeeId);
      }
    },

    data() {
      return {
        lendeeSelected: -1,
        lendeeName: ''
      }
    },

    methods: {
      addLendee() {
        this.$emit('lendee-name-added', this.lendeeName);
      }
    },

    computed: {
      lendeesData() {
        return this.lendees;
      }
    }
  };
</script>

<style scoped>

</style>