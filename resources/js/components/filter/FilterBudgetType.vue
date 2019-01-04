<template>
    <multiselect class="search-box" 
        :value="budgetType"
        @input="setBudgetType"
        :options="types" 
        placeholder="Budget Type" 
        selectLabel=">" 
        deselectLabel="x">
    </multiselect>
</template>


<script>
import { mapGetters, mapActions } from 'vuex';

export default {
    name: 'filter-budget-type-select',

    data() {
        return {
            types: ['current', 'original']
        }
    },

    computed: {
        ...mapGetters('filter', [
            'budgetType'
        ]),
    },

    methods: {
        ...mapActions('filter', [
            'setBudgetType'
        ])
    }
}
</script>
