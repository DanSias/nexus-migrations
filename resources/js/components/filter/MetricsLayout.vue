<template>
    <multiselect 
        :value="currentLayout" 
        @input="setColumnLayout"
        :options="layouts" 
        track-by="name"
        label="label"
        selectLabel=">"
        deselectLabel="x"
        placeholder="Table Layout">
    </multiselect>
</template>

<script>
import { mapState, mapGetters, mapActions } from 'vuex';

export default {
    name: 'metrics-layout-select',

    computed: {
        ...mapGetters('metrics', [
            'layouts'
        ]),
        ...mapGetters('table', [
            'layout'
        ]),
        currentLayout() {
            return _.find(this.layouts, ['name', this.layout]);
        }
    },

    methods: {
        ...mapActions('table', [
            'setColumnLayout'
        ])
    }
}
</script>

