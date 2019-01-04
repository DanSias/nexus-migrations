<template>
    <multiselect 
        :value="currentMetric" 
        @input="setTermConversionMetric"
        :options="metrics" 
        track-by="name"
        label="label"
        selectLabel=">"
        deselectLabel="x"
        placeholder="Metric">
    </multiselect>
</template>

<script>
import { mapState, mapGetters, mapActions } from 'vuex';

export default {
    name: 'term-conversion-metric-select',

    data() {
        return {
            metrics: [
                {
                    name: 'leads',
                    label: 'Leads'
                },
                {
                    name: 'starts',
                    label: 'Starts'
                },
                {
                    name: 'cvrs',
                    label: 'CVRS'
                }
            ]
        }
    },

    computed: {
        ...mapGetters('conversion', [
            'termConversionTable'
        ]),
        currentMetric() {
            return _.find(this.metrics, ['name', this.termConversionTable.metric]);
        }
    },

    methods: {
        ...mapActions('conversion', [
            'setTermConversionMetric'
        ])
    }
}
</script>

