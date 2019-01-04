<template>
    <datepicker input-class="datepicker-input form-control"
        calendar-class="calendar-right"
        placeholder="Start Date" 
        v-model="startDate"
        :minimumView="'month'" 
        :maximumView="'month'" 
        format="MMMM yyyy"  >
    </datepicker>
</template>


<script>
    import { mapState, mapGetters, mapActions } from 'vuex';
    import Datepicker from "vuejs-datepicker";
    
    export default {
        name: 'year-month-start',

        computed: {
            ...mapState('date', [
                'range'
            ]),

            startDate: {
                get() {
                    let date = this.range.start;
                    if (date == '') {
                        return '';
                    }
                    let yr = date.substring(0,4);
                    let mo = date.substring(4,6) - 1;
                    let startDateTime = new Date(yr, mo);
                    return startDateTime;
                },
                set(value) {
                    console.log(value);
                    let date = new Date(value);
                    let mo = date.getMonth() + 1;
                    mo = this.pad(mo, 2);
                    let yr = date.getFullYear();
                    let string = String(yr) + String(mo);
                    this.setRangeStart(string)
                }
            }
        },

        methods: {
            ...mapActions('date', [
                'setRangeStart'
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
.datepicker-input {
    cursor: pointer !important;
    background-color: transparent !important;
}
</style>
