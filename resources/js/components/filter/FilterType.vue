<template>
    <multiselect class="search-box" 
        :value="type"
        @input="setFilterType"
        :options="levels" 
        placeholder="Degree Type" 
        selectLabel=">" 
        deselectLabel="x">
    </multiselect>
</template>


<script>
import { mapGetters, mapActions } from 'vuex';

export default {
    name: 'filter-type-select',

    computed: {
        ...mapGetters('filter', [
            'type',
        ]),
        ...mapGetters('selects', [
            'levels'
        ])
    },

    methods: {
        ...mapActions('filter', [
            'setFilterType'
        ])
    }
}
</script>
