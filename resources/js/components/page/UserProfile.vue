<template>
    <div>
        <label class="uk-form-label">Team</label>
            <div class="uk-form-controls">
                <user-team></user-team>
            </div>

            <div class="between"></div>

            <label class="uk-form-label">Role</label>
            <div class="uk-form-controls">
                <user-title></user-title>
            </div>

            <div class="between"></div>

            <label class="uk-form-label">Focus Locations</label>
            <div class="uk-form-controls">
                <user-location></user-location>
            </div>

            <div class="between"></div>

            <label class="uk-form-label">Focus Business Units</label>
            <div class="uk-form-controls">
                <user-bu></user-bu>
            </div>

            <div class="between"></div>

            <label class="uk-form-label">Focus Channels</label>
            <div class="uk-form-controls">
                <user-channel></user-channel>
            </div>

            <div class="between"></div>

            <label class="uk-form-label">Extension</label>
            <div class="uk-form-controls">
                <user-extension></user-extension>
            </div>

            <div class="between"></div>

            <vk-button type="primary" class="uk-width-1-1" @click.prevent="updateUser()">Update Profile</vk-button>
    </div>
</template>

<script>
import { mapGetters, mapActions } from 'vuex';
import axios from 'axios';

import UserTeam from '../selects/UserTeam';
import UserTitle from '../selects/UserTitle.vue';
import UserLocation from '../selects/UserLocation.vue';
import UserBu from '../selects/UserBu.vue';
import UserChannel from '../selects/UserChannel.vue';
import UserExtension from '../selects/UserExtension.vue';
import BudgetSettings from '../budget/Settings';

export default {
    name: 'nexus-user-profile',

    computed: {
        ...mapGetters('user', [
            'myName',
            'myPicture',
            'myTeam',
            'myRole',
            'myPrograms',
            'myEmail',
            'form',
            'profilePage',
        ]),
    },


    methods: {
        ...mapActions('user', [
            'setMyForm'
        ]),
        ...mapActions('page', [
            'addMessage'
        ]),

        updateUser() {
            axios
                .post('/update-user', {
                    user: {
                        email: this.myEmail,
                        form: this.form
                    }
                })
                .then(response => {
                    let data = response.data;
                    if (data.email == this.myEmail) {
                        this.addMessage('Profile Saved!');
                    }
                });
        },
    },

    components: {
        UserTeam,
        UserTitle,
        UserLocation,
        UserBu,
        UserChannel,
        UserExtension,
    },

    mounted() {
        this.setMyForm();
    }
}
</script>

<style scoped>
.between {
    height: 1rem;
}
</style>