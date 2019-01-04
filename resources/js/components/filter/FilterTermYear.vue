<template>
    <multiselect class="search-box" 
        :value="termYear"
        @input="setFilterTermYear"
        :options="years" 
        placeholder="" 
        selectLabel=">" 
        deselectLabel="x">
    </multiselect>
</template>


<script>
import { mapGetters, mapActions } from 'vuex';

export default {
    name: 'filter-term-year-select',

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
            'setFilterTermYear'
        ])
    }
}
</script>
