<template>
    <multiselect class="search-box" 
        :value="currentOrderObject"
        @input="setFilterOrder"
        :options="orders" 
        placeholder="Order"
        track-by="sort"
        label="name"
        selectLabel=">" 
        deselectLabel="x">
    </multiselect>
</template>


<script>
import { mapGetters, mapActions } from 'vuex';

export default {
    name: 'sidebar-order-select',

    computed: {
        ...mapGetters('selects', [
            'orders',
        ]),
        ...mapGetters('filter', [
            'sort',
            'order',
        ]),
        currentOrderObject() {
            let sort = this.sort;
            let order = this.order;
            let allOrders = this.orders;

            let find = _.find(allOrders, { 'sort': sort, 'order': order });
            return find;
        }
    },


    methods: {
        ...mapActions('filter', [
            'setFilterOrder'
        ])
    }
}
</script>