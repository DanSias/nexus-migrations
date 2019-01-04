<template>
    <multiselect class="search-box" 
        :value="form.focus_location"
        :multiple="true"
        :close-on-select="false"
        @input="setFormLocation"
        :options="locations" 
        placeholder="All Locations" 
        selectLabel=">" 
        deselectLabel="x">
    </multiselect>
</template>


<script>
    import { mapGetters, mapActions } from 'vuex';
    
    export default {
        name: 'user-location-select',

        computed: {
            ...mapGetters('selects', [
                'locations'
            ]),
            ...mapGetters('user', [
                'form'
            ]),
            locationArray() {
                let userLocation = form.focus_location;
                if (_.isString(userLocation)) {
                    return [userLocation];
                } else {
                    return userLocation;
                }
            }
        },

        methods: {
            ...mapActions('user', [
                'setFormLocation'
            ])
        }
    }
</script>
