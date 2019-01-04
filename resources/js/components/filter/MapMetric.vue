<template>
    <multiselect class="search-box" 
        :value="metric"
        @input="setMapMetric"
        :options="metrics" 
        placeholder="Metric" 
        selectLabel=">" 
        deselectLabel="x">
    </multiselect>
</template>


<script>
    import { mapGetters, mapActions } from 'vuex';
    
    export default {
        name: 'map-metric-select',

        data() {
            return {
                metrics: ['Leads', 'Starts', 'Students']
            }
        },
        
        computed: {
            ...mapGetters('map', [
                'metric',
            ])
        },

        methods: {
            ...mapActions('map', [
                'setMapMetric'
            ])
        }
    }
</script>
