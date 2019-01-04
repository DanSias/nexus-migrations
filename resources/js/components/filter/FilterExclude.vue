<template>
    <multiselect class="search-box" 
        :value="exclude"
        @input="setFilterExclude"
        :options="options" 
        :multiple="true"
        :close-on-select="true"
        :hide-selected="true"
        :clear-on-select="false"
        placeholder="Remove" 
        selectLabel=">" 
        deselectLabel="x">
    </multiselect>
</template>


<script>
import { mapGetters, mapActions } from 'vuex';

export default {
    name: 'filter-exclude-select',

    computed: {
        ...mapGetters('filter', [
            'exclude',
            'excludeGroup'
        ]),
        ...mapGetters('selects', [
            'locations',
            'programs',
            'channels',
            'verticals'
        ]),
        ...mapGetters('attributes', [
            'filteredGroupList',
            'filteredProgramsList'
        ]),

        options() {
            let group = this.excludeGroup.toLowerCase();
            switch (group) {
                case 'location':
                    return this.locations;
                    break;

                case 'program': 
                    return this.filteredProgramsList;
                    break;

                case 'channel': 
                    return this.channels;
                    break;

                case 'vertical': 
                    return this.verticals;
                    break;
            
                default:
                    return [];
                    break;
            }
        }
    },

    methods: {
        ...mapActions('filter', [
            'setFilterExclude'
        ])
    }
}
</script>
