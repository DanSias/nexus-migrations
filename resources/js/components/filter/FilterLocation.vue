<template>
    <multiselect class="search-box" 
        :value="location"
        :multiple="true"
        :close-on-select="false"
        @input="setFilterLocation"
        :options="locations" 
        placeholder="Location" 
        selectLabel=">" 
        deselectLabel="x">
    </multiselect>
</template>


<script>
    import { mapGetters, mapActions } from 'vuex';
    
    export default {
        name: 'sidebar-location-select',

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
