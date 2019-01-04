<template>
    <multiselect class="search-box" 
        :value="year"
        @input="setYear"
        :options="metrics" 
        placeholder="Metric" 
        selectLabel=">" 
        deselectLabel="x">
    </multiselect>
</template>


<script>
    import { mapState, mapGetters, mapActions } from 'vuex';
    
    export default {
        name: 'revenue-metric-select',

        data() {
            return {
                metrics: ['Starts', 'Students', 'Revenue']
            }
        },

        computed: {
            ...mapState('revenue', [
                'year',
            ]),
            ...mapGetters('selects', [
                'years'
            ]),
        },

        methods: {
            ...mapActions('revenue', [
                'setYear'
            ]),
            updateRevenueData() {
                
            }
        }
    }
</script>
