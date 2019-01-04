<template>
    <multiselect class="search-box" 
        :value="excludeChannels"
        @input="setFilterExcludeChannels"
        :options="channels" 
        placeholder="Exclude Channels" 
        selectLabel=">" 
        deselectLabel="x">
    </multiselect>
</template>


<script>
import { mapGetters, mapActions } from 'vuex';

export default {
    name: 'filter-exclude-channel-select',

    computed: {
        ...mapGetters('filter', [
            'excludeChannels'
        ]),
        ...mapGetters('selects', [
            'channels'
        ])
    },

    methods: {
        ...mapActions('filter', [
            'setFilterExcludeChannels'
        ])
    }
}
</script>
