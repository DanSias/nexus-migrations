<template>
    <span>
        <portal to="nexus-avatar">
            <img @click="reveal()" class="uk-border-circle avatar" :src="myPicture" width="40" height="40" alt="" v-vk-tooltip.bottom="{ title: 'Profile'}">
        </portal>

        <!-- Off-Canvas Menu -->
        <vk-offcanvas-content>
            <vk-offcanvas flipped overlay :show.sync="showOffCanvas">
                <vk-offcanvas-close @click="showOffCanvas = false"></vk-offcanvas-close>
                <nexus-off-canvas></nexus-off-canvas>
            </vk-offcanvas>
        </vk-offcanvas-content>
    </span>
</template>

<script>
import { mapGetters, mapActions } from 'vuex';

import NexusOffCanvas from './OffCanvas';

export default {
    name: 'nexus-footer',

    data() {
        return {
            showOffCanvas: false,
        }
    },

    computed: {
        ...mapGetters('user', [
            'myPicture'
        ]),
    },

    methods: {
        ...mapActions('user', [
            'setMyForm'
        ]),

        reveal() {
            this.setMyForm();
            this.showOffCanvas = true;
        }
    },

    components: {
        NexusOffCanvas,
    }
}
</script>

<style scoped>
.avatar {
    cursor: pointer;
    margin-top: 1.25rem;
    margin-left: 1.05rem;
}
</style>
