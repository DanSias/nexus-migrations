<template>
    <multiselect class="search-box" 
        :value="vintage"
        @input="setFilterVintage"
        :options="vintageOptions" 
        :placeholder="placeholder" 
        selectLabel=">" 
        deselectLabel="x">
    </multiselect>
</template>


<script>
import { mapGetters, mapActions } from 'vuex';

export default {
    name: 'filter-vintage-select',

    data() {
        return {
            yearVintages: [2018, 2017, 2016, 2015, 2014, 2013],
            mvuVintages: ['All Maryville', 'Existing Programs', 'New Suite']
        }
    },

    computed: {
        ...mapGetters('filter', [
            'location',
            'vintage'
        ]),
        placeholder() {
            if (this.location == 'MVU') {
                return 'All Maryville';
            } else {
                return 'All Years';
            }
        },
        vintageOptions() {
            if (this.location == 'MVU') {
                return this.mvuVintages;
            } else {
                return this.yearVintages;
            }
        }
    },

    methods: {
        ...mapActions('filter', [
            'setFilterVintage'
        ])
    }
}
</script>
