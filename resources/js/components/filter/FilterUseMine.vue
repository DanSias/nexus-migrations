<template>
    <span v-if="myPrograms && myPrograms.length > 0">
        <div class="checkbox uk-margin uk-margin-small-top" @click="toggleMine()">
            <input type="checkbox" id="mine" v-model="useMine">
            <label for="check-1">Use My Programs ({{ myPrograms.length }})</label>
        </div>
    </span>
</template>

<script>
import { mapGetters, mapActions } from 'vuex';

export default {
    name: 'filter-use-mine-select',

    computed: {
        ...mapGetters('user', [
            'myPrograms'
        ]),
        ...mapGetters('filter', [
            'useMine'
        ]),
    },
    
    methods: {
        ...mapActions('filter', [
            'toggleFilterMine',
        ]),
        toggleMine() {
            console.log('this is the use mine checkbox');
            this.toggleFilterMine();
        },
    }
}
</script>

<style scoped>
.checkbox input+label, .radio input+label {
    z-index: 0;
}
</style>
