<template>
    <aside class="w-100 px-1 py-2 d-flex flex-column align-items-center justify-content-start" style="height: 100%;">
        <div class="top-auth-wrapper">
            <img :src="authStore.userData?.profile_picture" class="auth-img" />
            <div class="d-flex flex-column align-items-start content-wrapper">
                <h5 class="">{{ authStore.userData?.name }}</h5>
                <p>{{ authStore.userData?.email ?? authStore.userData?.phone }}</p>
            </div>
        </div>
        <div class="w-100 d-flex flex-column h-100">
            <DashboardMenu />
        </div>
    </aside>
</template>

<script setup>
import { useAuthStore } from "@/stores/auth";
import DashboardMenu from "./DashboardMenu.vue";
const authStore = useAuthStore();
</script>

<style scoped>
.top-auth-wrapper {
    width: 100%;
    display: flex;
    align-items: center;
    gap: 0.75rem;
    padding: 12px;
    background: transparent;
    border: 4px solid #9e4aed;
    margin-bottom: 22px;
    border-radius: 12px;
    transition: all 0.3s ease-in-out;
    cursor: pointer;
}

.top-auth-wrapper:hover {
    transform: scale(1.02);
    border-color: #64748B;
    background: #F8FAFC;
}

.top-auth-wrapper h5 {
    font-size: 16px;
    font-style: normal;
    font-weight: 500;
    line-height: 24px;
    color: #9e4aed;
    margin-bottom: 0;
    padding-bottom: 0;
}

.top-auth-wrapper p {
    color: #64748B;
    font-size: 12px;
    font-style: normal;
    font-weight: 400;
    line-height: 16px;
    margin-bottom: 0;
    padding-bottom: 0;
}

.auth-img {
    border-radius: 50%;
    width: 80px;
    height: 80px;
    border: 2px solid #f2f2f2;
    object-fit: cover;
    background: #ffffff;
    box-shadow: 0px 2px 2px 0px rgba(0, 0, 0, 0.25);
}


@media(max-width: 1600px) {
    .top-auth-wrapper {
        flex-direction: column;
        align-items: center;
    }

    .auth-img {
        width: 60px;
        height: 60px;
    }

    .content-wrapper {
        align-items: center !important;
    }

    .top-auth-wrapper .content-wrapper h5 {
        font-size: 14px;
    }

    .top-auth-wrapper .content-wrapper p {
        font-size: 12px;
    }
}


</style>
