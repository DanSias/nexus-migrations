<template>
    <multiselect class="search-box" 
        :value="year"
        @input="setMapYear"
        :options="years" 
        placeholder="Year" 
        selectLabel=">" 
        deselectLabel="x">
    </multiselect>
</template>


<script>
    import { mapGetters, mapActions } from 'vuex';
    
    export default {
        name: 'map-year-select',

        data() {
            return {
                years: [2017, 2016, 2015, 2014]
            }
        },

        computed: {
            ...mapGetters('map', [
                'year',
            ])
        },

        methods: {
            ...mapActions('map', [
                'setMapYear'
            ])
        }
    }
</script>
