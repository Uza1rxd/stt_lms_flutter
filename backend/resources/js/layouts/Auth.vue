<template>
    <div class="wrapper">
        <DashboardHeader />
        <div class="px-3 flex-grow-1 d-flex flex-column">
            <main class="h-100 d-flex flex-column flex-grow-1">
                <section class="row flex-grow-1">
                    <section class="border-end d-none d-xl-block col-lg-4 col-xl-3 col-xxl-3">
                        <DashboardSidebar />
                    </section>
                    <section class="col-lg-8 col-xl-9 col-xxl-9 px-4 pt-3 flex-grow-1" style="background: #F8FAFC;">
                        <button class="d-xl-none btn theme-shadow btn-outline-primary px-4 py-2 rounded-1 mt-3"
                            type="button" data-bs-toggle="offcanvas" data-bs-target="#dashboardMenu"
                            aria-controls="dashboardMenu">
                            Menu <i class="bi bi-arrow-right-circle"></i>
                        </button>

                        <!-- Mobile Menu -->
                        <div class="offcanvas offcanvas-start" tabindex="-1" id="dashboardMenu">
                            <div class="offcanvas-header">
                                <button type="button"
                                    class="theme-shadow btn-outline-primary px-4 py-2 rounded-1 btn m-0"
                                    data-bs-dismiss="offcanvas" aria-label="Close">
                                    <i class="bi bi-x-circle"></i>
                                </button>
                            </div>
                            <div class="offcanvas-body">
                                <DashboardMenu />
                            </div>
                        </div>


                        <slot />

                    </section>
                </section>
            </main>
        </div>
        <DashboardFooter />
    </div>
</template>

<script setup>
import DashboardFooter from "../components/DashboardFooter.vue";
import DashboardHeader from "../components/DashboardHeader.vue";

import DashboardSidebar from "../components/DashboardSidebar.vue";
import DashboardMenu from "../components/DashboardMenu.vue";
</script>

<style scoped>
.wrapper {
    display: flex;
    min-height: 100vh;
    flex-direction: column;
    justify-content: space-between;
}
</style>
