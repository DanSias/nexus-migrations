<template>
    <input type="number" class="form-control search-box match-select-height uk-input" @keyup="setFormExtension($event.target.value)" :value="form.extension">
</template>


<script>
    import { mapGetters, mapActions } from 'vuex';
    
    export default {
        name: 'user-extension-select',

        computed: {
            ...mapGetters('user', [
                'form'
            ]),
        },

        methods: {
            ...mapActions('user', [
                'setFormExtension'
            ])
        }
    }
</script>

<style scoped>
.match-select-height {
    height: 40px;
}
input[type="number"]::-webkit-outer-spin-button, input[type="number"]::-webkit-inner-spin-button {
    -webkit-appearance: none;
    margin: 0;
}
input[type="number"] {
    -moz-appearance: textfield;
}
</style>
