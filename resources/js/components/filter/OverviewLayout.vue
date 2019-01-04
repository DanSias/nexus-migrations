<template>
    <multiselect 
        v-model="layout" 
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
    name: 'overview-layout-select',

    data() {
        return {
            layout: {},
            layouts: [
                {
                    name: 'contact',
                    label: 'Contact',
                    cols: ['name', 'contact', 'leads', 'contactRate', 'spend', 'cpcontact']
                },
                {
                    name: 'contact15',
                    label: 'Contact 15',
                    cols: ['name', 'contact15', 'leads', 'contact15Rate', 'spend', 'cpcontact15']
                },
                {
                    name: 'quality',
                    label: 'Quality',
                    cols: ['name', 'quality', 'leads', 'qualityRate', 'spend', 'cpquality']
                },
                {
                    name: 'quality30',
                    label: 'Quality 30',
                    cols: ['name', 'quality30', 'leads', 'quality30Rate', 'spend', 'cpquality30']
                },
            ]
        }
    },

    computed: {
    },

    methods: {
        ...mapActions('table', [
            'setColumnLayout'
        ])
    }
}
</script>

