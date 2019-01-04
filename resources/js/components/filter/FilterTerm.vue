<template>
    <multiselect class="search-box m-t-0"
        :value="term"
        @input="setFilterTerm"
        :options="termsFiltered"
        placeholder="Term"
        selectLabel=">"
        deselectLabel="x">
    </multiselect>
</template>

<script>
import { mapGetters, mapActions } from 'vuex';

export default {
    name: 'filter-term-select',

    data() {
        return {
            terms: ['A1', 'A2', 'A3', 'B1', 'B2', 'B3', 'C1', 'C2', 'C3']
        }
    },

    computed: {
        ...mapGetters('filter', [
            'semester',
            'term'
        ]),
        termsFiltered() {
            switch (this.semester) {
                case 'Spr': 
                case 'Spring': 
                    return ['A1', 'A2', 'A3'];
                    break;
                case 'Sum': 
                case 'Summer': 
                    return ['B1', 'B2', 'B3'];
                    break;
                case 'Fall': 
                    return ['C1', 'C2', 'C3'];
                    break;
                default:
                    return this.terms;
                    break;
            }
        }
    },

    methods: {
        ...mapActions('filter', [
            'setFilterTerm',
        ]),
        
    },
}
</script>
