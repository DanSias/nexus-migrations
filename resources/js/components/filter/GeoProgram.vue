<template>
    <multiselect class="search-box" 
        :value="program"
        @input="setProgram"
        :options="programs" 
        placeholder="Program" 
        selectLabel=">" 
        deselectLabel="x">
    </multiselect>
</template>


<script>
import { mapState, mapActions, mapMutations } from 'vuex';

export default {
    name: 'geotargeting-program-select',

    data() {
        return {
            programs: [
                'OU-MBA',
                'PEP-MBA',
                'UC-MHA'
            ]
        }
    },

    computed: {
        ...mapState('filter', [
            'program'
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