<template>
    <multiselect class="" 
        :value="metricValue"
        @input="setChartMetric"
        :options="metrics" 
        track-by="value"
        label="label"
        placeholder="Chart Metric" 
        selectLabel=">" 
        deselectLabel="x">
    </multiselect>
</template>


<script>
import { mapGetters, mapActions } from 'vuex';

export default {
    name: 'chart-metric-select',

    data() {
        return {
            metrics: [
                {
                    label: 'Leads',
                    value: 'leads'
                }, {
                    label: 'Spend',
                    value: 'spend'
                }, {
                    label: 'CPL',
                    value: 'cpl'
                }, {
                    label: 'Contact',
                    value: 'contact'
                }, {
                    label: 'Contact 15',
                    value: 'contact15'
                }, {
                    label: 'Quality',
                    value: 'quality'
                }, {
                    label: 'Quality 30',
                    value: 'quality30'
                }
            ]
        }
    },

    computed: {
        ...mapGetters('chart', [
            'metric',
        ]),
        metricValue() {
            let metric = this.metric;
            let find = _.find(this.metrics, ['value', metric]);
            return find;
        }
    },

    methods: {
        ...mapActions('chart', [
            'setChartMetric'
        ])
    }
}
</script>
