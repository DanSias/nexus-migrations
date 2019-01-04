<template>
    <multiselect class="search-box" 
        :value="subvertical"
        @input="setFilterSubvertical"
        :options="options" 
        placeholder="Sub-Vertical" 
        selectLabel=">" 
        deselectLabel="x">
    </multiselect>
</template>


<script>
import { mapGetters, mapActions } from 'vuex';

export default {
    name: 'filter-subvertical-select',

    computed: {
        ...mapGetters('selects', [
            'verticals',
            'subverticals'
        ]),
        ...mapGetters('filter', [
            'vertical',
            'subvertical'
        ]),
        options() {
            if (this.vertical == '' || this.vertical == null) {
                return [];
            } else {
                let arr = this.subverticals[this.vertical].sort();
                return arr;
            }
        }
    },

    methods: {
        ...mapActions('filter', [
            'setFilterSubvertical'
        ])
    }
}
</script>
