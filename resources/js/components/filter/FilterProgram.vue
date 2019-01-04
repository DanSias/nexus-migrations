<template>
    <multiselect class="search-box" 
        :value="program"
        @input="setProgram"
        :options="filteredProgramsList" 
        placeholder="Program" 
        selectLabel=">" 
        deselectLabel="x">
    </multiselect>
</template>


<script>
import { mapState, mapGetters, mapActions, mapMutations } from 'vuex';

export default {
    name: 'sidebar-program-select',

    computed: {
        ...mapGetters('filter', [
            'program'
        ]),
        ...mapGetters('attributes', [
            'filteredProgramsList'
        ]),
        ...mapState('page', [
            'type'
        ]),
    },

    methods: {
        ...mapActions('filter', [
            'setFilterProgram',
        ]),
        ...mapMutations('filter', [
            'SET_FILTER_GROUP'
        ]),

        setProgram(program) {
            if (this.type == 'overview') {
                this.SET_FILTER_GROUP('Channel')
            } 
            this.setFilterProgram(program);
        }
    }
};
</script>