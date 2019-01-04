<template>
    <div>
        <vk-sticky>
            <vk-navbar class="uk-light uk-animation-slide-top">
                <!-- neXus Logo -->
                <nexus-brand></nexus-brand>

                <vk-navbar-nav slot="right" class="right-menu">

                    <!-- Page Tour -->
                    <!-- <vk-navbar-nav-item 
                        v-if="type == ''"
                        icon="question" 
                        @click.prevent="showFeedback = true" 
                        v-vk-tooltip.bottom="{ title: 'About This Page', offset: 0 }"
                    >
                    </vk-navbar-nav-item> -->

                    <vk-navbar-nav-item 
                        icon="comment" 
                        @click.prevent="showFeedback = true" 
                        v-vk-tooltip.bottom="{ title: 'Leave Feedback', offset: 0 }"
                    >
                    </vk-navbar-nav-item>

                    <!-- <vk-navbar-toggle class="ui-margin-right"></vk-navbar-toggle> -->
                    <vk-navbar-nav-item 
                        icon="grid"
                        v-vk-tooltip.bottom="{ title: 'neXus Dashboards', offset: 0 }"
                    >
                    </vk-navbar-nav-item>

                    <vk-drop mode="click" class="uk-width-xlarge">
                        <navigation-card></navigation-card>
                    </vk-drop>

                    <!-- User Avatar, Click to show off-canvas menu -->
                    <portal-target name="nexus-avatar"></portal-target>
                </vk-navbar-nav>

            </vk-navbar>
        </vk-sticky>


        <!-- Feedback Modal -->
        <vk-modal :show.sync="showFeedback">
            <nexus-feedback @close="showFeedback = false"></nexus-feedback>
        </vk-modal>

        <!-- Notification -->
        <vk-notification :messages.sync="messages">
            <div slot-scope="{ message }">
                <font-awesome-icon class="fa-fw uk-text-success" icon="check-circle" /> {{ message }}
            </div>
        </vk-notification>
    </div>
</template>

<script>
import { mapState, mapGetters, mapActions } from 'vuex';

import NexusFeedback from './Feedback';
import NexusBrand from './Brand';
import NavigationCard from './NavigationCard';

export default {
    name: 'nexus-header',

    data() {
        return {
            showFeedback: false,
            showOffCanvas: false,
        }
    },

    computed: {
        ...mapGetters('user', [
            'myPicture'
        ]),
        ...mapState('page', [
            'messages',
            'type'
        ]),
    },

    components: {
        NexusFeedback,
        NexusBrand,
        NavigationCard
    }
}
</script>

<style scoped>
.right-menu {
    margin-right: 2rem;
}
img {
    height: 40px;
    margin-top: 1.25rem;
}
.header-link {
    margin-bottom: 1.5rem;
    padding-top: 1.5rem;
}
.uk-navbar-container:not(.uk-navbar-transparent) {
    /* background: #fff; */
    background: linear-gradient(to right, #288AED , #32A6F2);
}
</style>
