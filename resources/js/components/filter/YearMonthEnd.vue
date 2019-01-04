<template>
    <datepicker input-class="datepicker-input form-control"
        calendar-class="calendar-right"
        placeholder="End Date" 
        v-model="endDate"
        :minimumView="'month'" 
        :maximumView="'month'" 
        format="MMMM yyyy"  >
    </datepicker>
</template>


<script>
    import { mapState, mapGetters, mapActions } from 'vuex';
    import Datepicker from "vuejs-datepicker";
    
    export default {
        name: 'year-month-end',

        computed: {
            ...mapState('date', [
                'range'
            ]),

            endDate: {
                get() {
                    let date = this.range.end;
                    if (date == '') {
                        return '';
                    }
                    let yr = date.substring(0,4);
                    let mo = date.substring(4,6) - 1;
                    let endDateTime = new Date(yr, mo);
                    return endDateTime;
                },
                set(value) {
                    console.log(value);
                    let date = new Date(value);
                    let mo = date.getMonth() + 1;
                    mo = this.pad(mo, 2);
                    let yr = date.getFullYear();
                    let string = String(yr) + String(mo);
                    this.setRangeEnd(string)
                }
            }
        },

        methods: {
            ...mapActions('date', [
                'setRangeEnd'
            ]),

            pad(a, b) {
                return(1e15 + a + "").slice(-b);
            },
        },

        components: {
            'datepicker': Datepicker,
        }
    }
</script>

<style>
.calendar-right {
    right: .1rem;
}
</style>
