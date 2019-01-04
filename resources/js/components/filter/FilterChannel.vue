<template>
    <multiselect class="search-box" 
        :value="channel"
        :multiple="true"
        :close-on-select="false"
        @input="setFilterChannel"
        :options="channels" 
        placeholder="All Channels" 
        selectLabel=">" 
        deselectLabel="x">
    </multiselect>
</template>


<script>
import { mapGetters, mapActions } from 'vuex';

export default {
    name: 'filter-channel-select',

    computed: {
        ...mapGetters('filter', [
            'channel'
        ]),
        ...mapGetters('selects', [
            'channels'
        ])
    },

    methods: {
        ...mapActions('filter', [
            'setFilterChannel'
        ])
    }
}
</script>
