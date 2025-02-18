<script>
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import Dropdown from '@/Components/Dropdown.vue';
import DropdownLink from '@/Components/DropdownLink.vue';
import NavLink from '@/Components/NavLink.vue';
import ResponsiveNavLink from '@/Components/ResponsiveNavLink.vue';
import { Link } from '@inertiajs/vue3';

export default {
    components: {
        ApplicationLogo,
        Dropdown,
        DropdownLink,
        NavLink,
        ResponsiveNavLink,
        Link
    },
    data() {
        return {
            current: 0,
            timer: null,
            transitionName: "fade",
            show: false,
            slides: [
                {
                    image: "https://images.unsplash.com/photo-1621905252507-b35492cc74b4?q=80&w=1469&auto=format&fit=crop&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D",
                },
                {
                    image: "https://plus.unsplash.com/premium_photo-1661715592317-e0268ac4e158?q=80&w=1470&auto=format&fit=crop&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D",
                },
                {
                    image: "https://plus.unsplash.com/premium_photo-1661515203486-ab9c1770f486?q=80&w=1470&auto=format&fit=crop&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D",
                }
            ]
        };
    },
    methods: {
        next() {
            this.transitionName = "slide-next";
            const len = this.slides.length;
            this.current = (this.current + 1) % len;
        },
        prev() {
            this.transitionName = "slide-prev";
            const len = this.slides.length;
            this.current = (this.current - 1 + len) % len;
        },
        startRotation() {
            this.timer = setInterval(this.next, 4000);
        },
        stopRotation() {
            clearInterval(this.timer);
            this.timer = null;
        }
    },
    mounted() {
        this.show = true;
        this.startRotation();
    },
    beforeUnmount() {
        this.stopRotation();
    }
};
</script>


<template>
    <div class="min-h-screen flex items-center justify-center bg-gray-200">
        <div class="w-screen h-screen flex">
            <!-- Bagian Slider -->
            <div class="relative w-full h-full">
                <div class="slider">
                    <transition-group tag="div" v-bind:name="transitionName">
                        <div v-if="show" v-bind:key="current" class="slider__item"
                            v-bind:style="{ 'background-image': 'url(' + slides[current].image + ')' }"
                            v-on:mouseover="stopRotation" v-on:mouseout="startRotation">
                            <a :href="slides[current].url" target="_blank" class="slider__item">
                                <h2><a :href="slides[current].url" target="_blank">{{ slides[current].title }}</a></h2>
                            </a>
                        </div>
                    </transition-group>
                </div>
            </div>

            <!-- Form Login -->
            <div
                class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 bg-white p-8 rounded-lg shadow-2xl drop-shadow-2xl">
                <div class="mb-6">
                    <ApplicationLogo class="h-20 w-20 fill-current text-gray-500" />
                </div>

                <div class="w-full">
                    <slot />
                </div>
            </div>
        </div>
    </div>
</template>




<style scoped>
.fade-enter-active {
    transition: opacity 0.5s ease-out, transform 0.5s ease-out;
}

.fade-enter {
    opacity: 0;
    transform: scale(0.95);
}

.slide-next-enter-active,
.slide-prev-enter-active {
    transition: transform 0.7s ease-in-out, opacity 0.5s ease-in-out;
    opacity: 1;
}

.slide-next-enter {
    opacity: 0;
    transform: translateX(100%);
}

.slide-prev-enter {
    opacity: 0;
    transform: translateX(-100%);
}

.slide-next-leave-active,
.slide-prev-leave-active {
    transition: transform 0.5s ease-in-out, opacity 0.5s ease-in-out;
    opacity: 0;
}

.slide-next-leave-to {
    transform: translateX(-100%);
}

.slide-next-enter-active,
.slide-prev-enter-active {
    filter: blur(10px);
    animation: blurFadeIn 0.8s ease-in-out forwards;
}

@keyframes blurFadeIn {
    from {
        filter: blur(10px);
        opacity: 0;
    }

    to {
        filter: blur(0);
        opacity: 1;
    }
}

.slider {
    width: 100%;
    height: 100vh;
    position: relative;
    overflow: hidden;
}

.slider__item {
    width: 100%;
    height: 100vh;
    background-size: cover;
    background-position: center;
    position: absolute;
    top: 0;
    left: 0;
    display: flex;
    align-items: center;
    justify-content: center;
    animation: zoomIn 0.8s ease-out forwards;
}

@keyframes zoomIn {
    from {
        transform: scale(1.05);
        opacity: 0;
    }

    to {
        transform: scale(1);
        opacity: 1;
    }
}

.slider__item h2 {
    color: #fff;
    font-size: 60px;
    text-shadow: 1px 1px 3px #000000;
    animation: fadeIn 1s ease-out forwards;
}

@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateY(10px);
    }

    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.btn {
    z-index: 10;
    cursor: pointer;
    border: 3px solid #fff;
    display: flex;
    justify-content: center;
    align-items: center;
    width: 50px;
    height: 50px;
    position: absolute;
    top: 50%;
    left: 30px;
    border-radius: 50%;
    transform: translateY(-50%);
    transition: transform 0.3s ease-out, opacity 0.3s ease-out, background 0.3s ease-in-out;
    user-select: none;
    color: #fff;
    opacity: 0.7;
    background: rgba(255, 255, 255, 0.2);
    backdrop-filter: blur(5px);
}

.btn-next {
    left: auto;
    right: 30px;
}

.btn:hover {
    color: #fff;
    opacity: 1;
    background: rgba(255, 255, 255, 0.5);
    transform: scale(1.1);
}

.text-container a {
    color: inherit;
}
</style>
