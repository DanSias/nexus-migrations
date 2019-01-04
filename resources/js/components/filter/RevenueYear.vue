<template>
    <multiselect class="search-box" 
        :value="year"
        @input="setYear"
        :options="years" 
        placeholder="Year" 
        selectLabel=">" 
        deselectLabel="x">
    </multiselect>
</template>


<script>
    import { mapGetters, mapActions } from 'vuex';
    
    export default {
        name: 'sidebar-year-select',

        computed: {
            ...mapGetters('selects', [
                'years'
            ]),
            ...mapGetters('revenue', [
                'year',
            ])
        },

        methods: {
            ...mapActions('revenue', [
                'setYear'
            ]),
            updateRevenueData() {
                
            }
        }
    }
</script>
