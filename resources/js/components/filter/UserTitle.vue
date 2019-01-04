<template>
    <multiselect class="search-box" 
        :value="form.role_title"
        @input="setFormTitle"
        :options="titleList" 
        placeholder="Title" 
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
            ...mapGetters('selects',[
                'teams'
            ]),
            ...mapGetters('user', [
                'form'
            ]),

            titleList() {
                let userTeam = this.form.team
                let team = this.teams;
                let list = team.filter(t => t.name == userTeam);
                if (list[0]) {
                    let levels = list[0].levels;
                    console.log(list);
                    return levels;
                } else {
                    return [];
                }
            },
        },

        methods: {
            ...mapActions('user', [
                'setFormTitle',
            ])
        }
    }
</script>
