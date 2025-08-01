<template>
    <div class="container py-5">
        <div class="row g-4">
            <!-- Left Side Skeleton -->
            <div class="col-lg-6">
                <div class="card shadow-sm rounded-4 p-4">
                    <div class="placeholder-glow mb-4">
                        <span class="placeholder col-6" style="height: 18px;"></span>
                    </div>
                    <div class="placeholder-glow mb-3">
                        <span class="placeholder col-12" style="height: 10px;"></span>
                    </div>
                    <div v-for="n in 3" :key="n" class="card mb-3 shadow-sm border-0 rounded-4">
                        <div class="row g-0">
                            <div class="col-md-4">
                                <div class="placeholder-glow w-100 h-100"
                                    style="height: 120px; background-color: #e0e0e0;"></div>
                            </div>
                            <div class="col-md-8 p-3">
                                <h5 class="placeholder-glow">
                                    <span class="placeholder col-6"></span>
                                </h5>
                                <p class="placeholder-glow mb-2">
                                    <span class="placeholder col-4"></span>
                                </p>
                                <p class="placeholder-glow">
                                    <span class="placeholder col-2"></span>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Side Skeleton -->
            <div class="col-lg-6">
                <div class="card shadow-sm rounded-4 p-4">
                    <div class="placeholder-glow mb-4">
                        <span class="placeholder col-6" style="height: 18px;"></span>
                    </div>
                    <ul class="list-group mb-3">
                        <li v-for="n in 2" :key="'right-course-' + n"
                            class="list-group-item d-flex justify-content-between align-items-center placeholder-glow">
                            <span class="placeholder col-5"></span>
                            <span class="placeholder col-2"></span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center placeholder-glow">
                            <span class="placeholder col-4"></span>
                            <span class="placeholder col-2"></span>
                        </li>
                    </ul>
                    <div class="placeholder-glow mb-3">
                        <span class="placeholder col-12" style="height: 38px;"></span>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <div class="placeholder-glow">
                                <span class="placeholder col-12" style="height: 38px;"></span>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="placeholder-glow">
                                <span class="placeholder col-12" style="height: 38px;"></span>
                            </div>
                        </div>
                    </div>
                    <div class="placeholder-glow">
                        <span class="placeholder col-12" style="height: 44px;"></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
