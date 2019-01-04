<template>
    <multiselect class="search-box" 
        :value="month"
        @input="setFilterMonth"
        :options="monthList" 
        :allow-empty="false"
        :custom-label="ucFirst"
        placeholder="Month" 
        selectLabel=">" 
        deselectLabel="x">
    </multiselect>
</template>


<script>
import { mapGetters, mapActions } from 'vuex';

export default {
    name: 'filter-month-select',

    computed: {
        ...mapGetters('filter', [
            'month'
        ]),
        ...mapGetters('selects', [
            'months'
        ]),
        monthList() {
            let list = this.months.slice(0);
            return list;
        }
    },

    methods: {
        ...mapActions('filter', [
            'setFilterMonth'
        ]),
        ucFirst(month) {
            return month.charAt(0).toUpperCase() + month.slice(1);
        }
    }
}
</script>
