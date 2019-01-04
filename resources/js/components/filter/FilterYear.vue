<template>
    <multiselect class="search-box" 
        :value="termYear"
        @input="setFilterYear"
        :options="years" 
        placeholder="Year" 
        selectLabel=">" 
        deselectLabel="x">
    </multiselect>
</template>


<script>
import { mapGetters, mapActions } from 'vuex';

export default {
    name: 'filter-year-select',

    computed: {
        ...mapGetters('selects', [
            'years'
        ]),
        ...mapGetters('filter', [
            'termYear',
        ])
    },

    methods: {
        ...mapActions('filter', [
            'setFilterYear'
        ])
    }
}
</script>
