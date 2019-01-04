<template>
    <div>
        <vk-tabs>
            <vk-tabs-item title="Profile">
                <user-profile></user-profile>
            </vk-tabs-item>
            <vk-tabs-item title="Programs">
                <user-programs></user-programs>
            </vk-tabs-item>
        </vk-tabs>
    </div>
</template>

<script>
import UserProfile from './UserProfile';
import UserPrograms from './UserPrograms';

export default {
    name: 'nexus-offcanvas',

    components: {
        UserProfile,
        UserPrograms,
    },
}
</script>

