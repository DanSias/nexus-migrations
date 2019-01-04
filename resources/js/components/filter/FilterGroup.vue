<template>
    <multiselect class="search-box" 
        :value="currentGroup"
        :options="groups"
        track-by="value"
        label="label"
        placeholder="Group"
        selectLabel=">" 
        deselectLabel="x" 
        @input="setGroup"
        :allow-empty="false">
    </multiselect>
</template>

<script>
import { mapGetters, mapActions } from 'vuex';

export default {
    name: 'filter-group-select',

    computed: {
        ...mapGetters('filter', [
            'group'
        ]),
        ...mapGetters('selects', [
            'groups'
        ]),
        currentGroup() {
            let g = _.filter(this.groups, ['value', this.group]);
            if (g == null) {
                g = _.filter(this.groups, ['label', this.group]);
            }
        }
    },

    methods: {
        ...mapActions('filter', [
            'setFilterGroup'
        ]),
        setGroup(obj) {
            let value = obj.value;
            this.setFilterGroup(value);
        }
    }
}
</script>
