<template>
    <multiselect class="search-box" 
        :value="school"
        @input="setFilterPartner"
        :options="schools" 
        placeholder="School" 
        selectLabel=">" 
        deselectLabel="x">
    </multiselect>
</template>


<script>
import { mapGetters, mapActions } from 'vuex';

export default {
    name: 'filter-school-select',

    computed: {
        ...mapGetters('selects', [
            'schools'
        ]),
        ...mapGetters('filter', [
            'school'
        ])
    },

    methods: {
        ...mapActions('filter', [
            'setFilterPartner'
        ])
    }
}
</script>

