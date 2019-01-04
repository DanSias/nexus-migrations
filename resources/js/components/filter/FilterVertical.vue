<template>
    <multiselect class="search-box" 
        :value="vertical"
        @input="setFilterVertical"
        :options="verticals" 
        placeholder="Vertical" 
        selectLabel=">" 
        deselectLabel="x">
    </multiselect>
</template>


<script>
import { mapGetters, mapActions } from 'vuex';

export default {
    name: 'filter-vertical-select',

    computed: {
        ...mapGetters('selects', [
            'verticals'
        ]),
        ...mapGetters('filter', [
            'vertical'
        ])
    },

    methods: {
        ...mapActions('filter', [
            'setFilterVertical'
        ])
    }
}
</script>
