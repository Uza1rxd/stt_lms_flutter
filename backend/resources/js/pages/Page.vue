<template>
    <section class="container py-5">
        <article v-if="page">
            <h1 class="text-center mb-5 text-uppercase">{{ page?.title }}</h1>
            <div v-html="page?.content"></div>
        </article>
        <div v-else class="text-start">
            <div class="skeleton mb-2" style="height: 10px; width: 1200px;"></div>
            <div class="skeleton mb-2" style="height: 10px; width: 1100px;"></div>
            <div class="skeleton mb-2" style="height: 10px; width: 1000px;"></div>
            <div class="skeleton mb-2" style="height: 10px; width: 900px;"></div>
            <div class="skeleton mb-2" style="height: 10px; width: 800px;"></div>
            <div class="skeleton mb-2" style="height: 10px; width: 700px;"></div>
            <div class="skeleton mb-2" style="height: 10px; width: 600px;"></div>
            <div class="skeleton mb-2" style="height: 10px; width: 500px;"></div>
            <div class="skeleton mb-2" style="height: 10px; width: 400px;"></div>
            <div class="skeleton mb-2" style="height: 10px; width: 300px;"></div>
            <div class="skeleton mb-2" style="height: 10px; width: 200px;"></div>
            <div class="skeleton mb-2" style="height: 10px; width: 100px;"></div>
            <div class="skeleton mb-2" style="height: 10px; width: 90px;"></div>
            <div class="skeleton mb-2" style="height: 10px; width: 80px;"></div>
            <div class="skeleton mb-2" style="height: 10px; width: 70px;"></div>
            <div class="skeleton mb-2" style="height: 10px; width: 60px;"></div>
            <div class="skeleton mb-2" style="height: 10px; width: 50px;"></div>
            <div class="skeleton mb-2" style="height: 10px; width: 40px;"></div>
            <div class="skeleton mb-2" style="height: 10px; width: 30px;"></div>
        </div>
    </section>
</template>

<style scoped>
.skeleton {
    background-color: #e0e0e0;
    border-radius: 4px;
    animation: shimmer 1.5s infinite;
}

@keyframes shimmer {
    0% {
        background-position: -200px 0;
        opacity: 0.9;
    }

    10%,
    30%,
    50%,
    70%,
    90% {
        background-position: 0 0;
        opacity: 1;
    }

    20%,
    40%,
    60%,
    80%,
    100% {
        background-position: 200px 0;
        opacity: 0.9;
    }
}
</style>

<script setup>
import { useRoute } from "vue-router";
import { useMasterStore } from "@/stores/master";
import { onMounted, ref, watchEffect } from "vue";

const route = useRoute();
const masterStore = useMasterStore();
const page = ref(null);

watchEffect(() => {
    const slug = route.params.slug;
    const pages = masterStore.masterData?.pages;

    if (slug && Array.isArray(pages)) {
        page.value = pages.find((p) => p.slug === slug);
        window.scrollTo(0, 0);
    }
});
</script>
