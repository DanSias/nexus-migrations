<template>
    <multiselect class="" 
        :multiple="true"
        :close-on-select="false"
        :clear-on-select="false"
        :value="metrics"
        @input="setMapMetrics"
        :options="metricsList" 
        placeholder="Metrics" 
        selectLabel=">" 
        deselectLabel="x">
    </multiselect>
</template>


<script>
import { mapState, mapActions } from 'vuex';

export default {
    name: 'geotargeting-metric-select',

    data() {
        return {
            metricsList: [
                'leadsyear1',
                'leadsyear2',
                'leadsyear3',
                'leadsyear4',
                'leadsyear5',
                'leadsyear6',
                'leadsyear7',
                'leadsyear8',
                'startsyear1',
                'startsyear2',
                'startsyear3',
                'startsyear4',
                'startsyear5',
                'startsyear6',
                'startsyear7',
                'startsyear8',
            ]
        }
    },

    computed: {
        ...mapState('map', [
            'metrics',
        ]),
        metricValue() {
            let metric = this.metric;
            let find = _.find(this.metrics, ['value', metric]);
            return find;
        }
    },

    methods: {
        ...mapActions('map', [
            'setMapMetrics'
        ])
    }
}
</script>
