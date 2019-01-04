<template>
    <multiselect class="search-box"
        :value="strategy"
        :multiple="true"
        :close-on-select="false"
        @input="setFilterStrategy"
        :options="strategies"
        placeholder="Spend Strategy"
        selectLabel=">"
        deselectLabel="x">
    </multiselect>
</template>

<script>
import { mapGetters, mapActions } from 'vuex';

export default {
    name: 'filter-strategy-select',

    data() {
        return {
            strategies: [
                'Invest',
                'Optimize',
                'Divest',
                'Cut'
            ]
        }
    },

    computed: {
        ...mapGetters('filter', [
            'strategy',
        ]),
    },

    methods: {
        ...mapActions('filter', [
            'setFilterStrategy',
        ]),
        
    },
}
</script>
