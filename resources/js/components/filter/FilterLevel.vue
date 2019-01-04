<template>
    <multiselect class="search-box" 
        :value="level"
        @input="setFilterLevel"
        :options="levels" 
        placeholder="Degree Level" 
        selectLabel=">" 
        deselectLabel="x">
    </multiselect>
</template>


<script>
import { mapGetters, mapActions } from 'vuex';

export default {
    name: 'filter-level-select',

    data() {
        return {
            levels: [
                'Undergraduate', 
                'Graduate'
            ]
        }
    },

    computed: {
        ...mapGetters('filter', [
            'level',
        ])
    },

    methods: {
        ...mapActions('filter', [
            'setFilterLevel'
        ])
    }
}
</script>
