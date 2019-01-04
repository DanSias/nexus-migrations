<template>
    <multiselect class="search-box m-t-0"
        :value="currentSemester"
        @input="setFilterSemester"
        :options="semesters"
        track-by="name"
        label="label"
        placeholder="Semester"
        selectLabel=">"
        deselectLabel="x">
    </multiselect>
</template>

<script>
import { mapGetters, mapActions } from 'vuex';

export default {
    data() {
        return {
            semesters: [
                {
                    name: 'Spr',
                    label: 'Spring'
                },
                {
                    name: 'Sum',
                    label: 'Summer'
                },
                {
                    name: 'Fall',
                    label: 'Fall'
                },
                {
                    name: null,
                    label: 'All'
                }
            ]
        }
    },

    computed: {
        ...mapGetters('filter', [
            'semester'
        ]),
        currentSemester() {
            let sem = this.semester;
            if (sem == null) {
                return {
                    name: null,
                    label: 'All'
                };
            }
            let setSemester = _.filter(this.semesters, {'name': sem});
            return setSemester;
        }
    },

    methods: {
        ...mapActions('filter', [
            'setFilterSemester',
        ]),
    },
}
</script>
