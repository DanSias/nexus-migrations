<template>
    <vk-card>
        <vk-grid class="uk-drop-grid uk-child-width-1-3@m">
        <div>
            <h4 class="nav-heading">Marketing</h4>
            <p class="nav-item">
                <router-link to="/metrics">
                    <a class="nav-list uk-margin-right" href="#"><font-awesome-icon icon="chart-line" fixed-width /> Metrics</a>
                </router-link>
            </p>
            <p class="nav-item">
                <a class="nav-list uk-margin-right" href="/overview"><font-awesome-icon icon="briefcase" fixed-width /> Overview</a>
            </p>
            <p class="nav-item">
                <a class="nav-list uk-margin-right" href="/leadership"><font-awesome-icon icon="sort-amount-down" fixed-width /> Leadership</a>
            </p>
            <p class="nav-item">
                <a class="nav-list uk-margin-right" href="#"><font-awesome-icon icon="key" fixed-width /> Tracker</a>
            </p>
        </div>
        <div>
            <h4 class="nav-heading">Recruitment</h4>
            <p class="nav-item">
                <router-link to="/recruitment/leading">
                    <a class="nav-list uk-margin-right" href="#"><font-awesome-icon icon="caret-right" fixed-width /> Leading</a>
                </router-link>
            </p>
            <p class="nav-item">
                <router-link to="/recruitment/lagging">
                    <a class="nav-list uk-margin-right" href="#"><font-awesome-icon icon="caret-left" fixed-width /> Lagging</a>
                </router-link>
            </p>
            <p class="nav-item">
                <a class="nav-list uk-margin-right" href="/budget/recruitment"><font-awesome-icon icon="balance-scale" fixed-width /> Budget</a>
            </p>
        </div>
        <div>
            <h4 class="nav-heading">Details</h4>
            <p class="nav-item">
                <a class="nav-list uk-margin-right" href="/attributes"><font-awesome-icon icon="graduation-cap" fixed-width /> Programs</a>
            </p>
            <p class="nav-item">
                <a class="nav-list uk-margin-right" href="/assets/pages"><font-awesome-icon icon="id-card" fixed-width /> Assets</a>
            </p>
            <p class="nav-item">
                <a class="nav-list uk-margin-right" href="/users"><font-awesome-icon icon="users" fixed-width /> Teams</a>
            </p>
            <p class="nav-item">
                <a class="nav-list uk-margin-right" href="/me"><font-awesome-icon icon="cogs" fixed-width /> Profile</a>
            </p>
        </div>
        </vk-grid>
    </vk-card>
</template>

<script>
export default {
    name: 'navigation-card',

}
</script>

<style scoped>
.nav-item {
    margin-bottom: .45rem;
    margin-top: .45rem;
}
.nav-heading {
    color: #444;
}
.nav-list {
    color: #666;
}
.nav-list:hover {
    color: #1e87f0;
    text-decoration: none;
}
</style>
