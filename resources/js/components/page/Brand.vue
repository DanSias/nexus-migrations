<template>
    <vk-navbar-nav>
        <router-link to="/home">
            <vk-navbar-logo >
                <span class="nexus-font">ne<span class="the-x">X</span>us</span>
            </vk-navbar-logo>
        </router-link>
    </vk-navbar-nav>
</template>

<script>
export default {
    name: 'nexus-brand',


}
</script>


<style scoped>
.nexus-font {
    font-family: 'Orbitron', sans-serif;
    font-size: 2.3rem;
    font-weight: 200;
    color: #fff;
    cursor: pointer;
    letter-spacing: 2px;
    transform: scaleX(1.2);
    margin-left: 2rem;
    transition-duration: .25s;
}
.nexus-font:hover {
    margin-left: 2.25rem;
    text-decoration: none;
}
a, u {
    text-decoration: none;
}
.the-x {
    color: #fff;
    opacity: .6;
}
</style>
