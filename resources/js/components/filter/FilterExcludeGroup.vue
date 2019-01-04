<template>
    <multiselect class="search-box" 
        :value="excludeGroup"
        :options="groups"
        placeholder="Exclude by Group"
        selectLabel=">" 
        deselectLabel="x" 
        @input="setFilterExcludeGroup">
    </multiselect>
</template>

<script>
import { mapGetters, mapActions } from 'vuex';

export default {
    name: 'filter-exclude-group-select',

    data() {
        return {
            groups: [
                'Location',
                'Program',
                'Vertical'
            ]
        }
    },

    computed: {
        ...mapGetters('filter', [
            'excludeGroup'
        ]),
        // ...mapGetters('selects', [
        //     'groups'
        // ])
    },

    methods: {
        ...mapActions('filter', [
            'setFilterExcludeGroup'
        ])
    }
}
</script>
