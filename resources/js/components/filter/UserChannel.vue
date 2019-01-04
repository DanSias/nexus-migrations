<template>
    <multiselect class="search-box" 
        :value="form.focus_channel"
        :multiple="true"
        :close-on-select="false"
        @input="setFormChannel"
        :options="channels" 
        placeholder="All Channels" 
        selectLabel=">" 
        deselectLabel="x">
    </multiselect>
</template>

<script>
    import { mapGetters, mapActions } from 'vuex';
    
    export default {
        name: 'user-channel-select',

        computed: {
            ...mapGetters('selects', [
                'channels'
            ]),
            ...mapGetters('user', [
                'form',
            ]),
        },

        methods: {
            ...mapActions('user', [
                'setFormChannel'
            ])
        }
    }
</script>
