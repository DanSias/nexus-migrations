<template>
    <multiselect class="search-box" 
        :value="form.team"
        @input="setFormTeam"
        :options="teamList" 
        placeholder="Team" 
        selectLabel=">" 
        deselectLabel="x">
    </multiselect>
</template>


<script>
    import { mapGetters, mapActions } from 'vuex';
    
    export default {
        name: 'user-team-select',

        data() {
            return {
                
            }
        },

        computed: {
            ...mapGetters('user', [
                'form'
            ]),
            ...mapGetters('selects', [
                'teams'
            ]),

            teamList() {
                let list = [];
                for (const t in this.teams) {
                    list.push(this.teams[t].name);
                }
                return list.sort();
            },
        },

        methods: {
            ...mapActions('user', [
                'setFormTeam',
            ])
        }
    }
</script>
