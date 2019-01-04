<template>
    <multiselect class="search-box" 
        v-model="location"
        :options="locations" 
        placeholder="Location" 
        selectLabel=">" 
        deselectLabel="x">
    </multiselect>
</template>


<script>
import { mapGetters, mapActions } from 'vuex';

export default {
    name: 'basic-location-select',

    data() {
        return {
            location: ''
        }
    },

    watch: {
        location() {
            this.$emit('location', this.location);
        }
    },

    computed: {
        ...mapGetters('filter', [
            'location'
        ]),
        ...mapGetters('selects', [
            'locations'
        ])
    },

    methods: {
        ...mapActions('filter', [
            'setFilterLocation'
        ])
    }
}
</script>
