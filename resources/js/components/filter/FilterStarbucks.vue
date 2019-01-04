<template>
    <div class="checkbox">
        <input type="checkbox" id="starbucks" v-model="checkbox">
        <label for="starbucks">Include Starbucks</label>
    </div>
</template>

<script>
import { mapGetters, mapActions } from 'vuex';

export default {
    name: 'filter-starbucks-select',

    computed: {
        ...mapGetters('filter', [
            'starbucks',
        ]),

        checkbox: {
            get () {
                return this.starbucks;
            },
            set (value) {
                this.setFilterStarbucks(value);
            }
        }
    },

    methods: {
        ...mapActions('filter', [
            'setFilterStarbucks',
        ]),
        
    },
}
</script>

<style scoped>
.checkbox input+label, .radio input+label {
    z-index: 0;
}
</style>
