<template>
    <div id="app">
        <Navbar />
        <router-view />
        <Footer />
    </div>
</template>

<script>
import Navbar from '../Components/Navbar.vue';
import Footer from '../Components/Footer.vue';

export default {
    components: {
        Navbar,
        Footer
    },
};
</script>

<style></style>