<template>
    <multiselect class="search-box" 
        :value="bu"
        :multiple="true"
        @input="setFilterBu"
        :options="buList" 
        placeholder="All Business Units" 
        selectLabel=">" 
        deselectLabel="x">
    </multiselect>
</template>


<script>
import { mapGetters, mapActions } from 'vuex';

export default {
    name: 'filter-bu-select',

    computed: {
        ...mapGetters('filter', [
            'bu',
            'location'
        ]),
        ...mapGetters('selects', [
            'units'
        ]),
        buList() {
            let fullList = this.units;
            fullList.unshift('');
            return fullList;
            // let location = this.location;

            // if (location === undefined) {
            //     return fullList;
            // }

            // let orlandoUnits = [1, 2, 3, 4, 5];
            // let torontoUnits = [6, 7, 8, 9];
            // let chicagoUnits = [10, 11, 13, 14];
            // let chandlerUnits = [16, 17];
            // let asuUnits = [18];
            // let mvuUnits = [12];

            // let array = [];

            // if (location.includes('Orlando')) {
            //     _.forEach(orlandoUnits, unit => array.push(unit));
            // }
            // if (location.includes('Toronto')) {
            //     _.forEach(torontoUnits, unit => array.push(unit));
            // }
            // if (location.includes('Chicago')) {
            //     _.forEach(chicagoUnits, unit => array.push(unit));
            // }
            // if (location.includes('Chandler')) {
            //     _.forEach(chandlerUnits, unit => array.push(unit));
            // }
            // if (location.includes('ASU')) {
            //      _.forEach(asuUnits, unit => array.push(unit));
            // }
            // if (location.includes('MVU')) {
            //      _.forEach(mvuUnits, unit => array.push(unit));
            // }
            // array.sort(function(a, b){return a - b});
            // return array;
        },
    },

    methods: {
        ...mapActions('filter', [
            'setFilterBu'
        ])
    }
}
</script>
