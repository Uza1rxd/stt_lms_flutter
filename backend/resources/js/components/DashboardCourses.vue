<template>
    <section class="my-2">
        <span class="d-block mb-2 mb-lg-3 fs-4 fw-bold">{{ $t('Home') }}/{{ $t('My Courses') }}</span>

        <ul class="nav nav-pills mb-3 bg-white p-3 rounded d-flex align-items-center gap-3" id="pills-tab" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="pills-courses-tab" data-bs-toggle="pill"
                    data-bs-target="#pills-courses" type="button" role="tab" aria-controls="pills-courses"
                    aria-selected="true">
                    {{ $t('All Courses') }}
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="pills-regular-tab" data-bs-toggle="pill" data-bs-target="#pills-regular"
                    type="button" role="tab" aria-controls="pills-regular" aria-selected="true">
                    {{ $t('Regular Courses') }}
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="pills-completed-tab" data-bs-toggle="pill"
                    data-bs-target="#pills-completed" type="button" role="tab" aria-controls="pills-completed"
                    aria-selected="false">
                    {{ $t('Completed Courses') }}
                </button>
            </li>
            <li class="nav-item position-relative" role="presentation">
                <button class="nav-link" id="pills-subscribed-tab" data-bs-toggle="pill"
                    data-bs-target="#pills-subscribed" type="button" role="tab" aria-controls="pills-subscribed"
                    aria-selected="false">
                    {{ $t('Subscribed Courses') }}
                </button>
                <div class="subscribe-badge">
                    <i class="bi bi-star-fill text-warning"></i>
                </div>
            </li>
        </ul>
        <div class="tab-content" id="pills-tabContent">
            <div class="tab-pane fade show active" id="pills-courses" role="tabpanel"
                aria-labelledby="pills-courses-tab" tabindex="0">
                <div class="row">
                    <div v-for="course in courses" :key="course.id" class="col-lg-6 col-xl-4 mb-4 position-relative">
                        <div class="bg-white theme-shadow rounded d-flex flex-column gap-2 p-3">
                            <img :src="course.thumbnail" alt="" height="140px" width="140px"
                                class="object-fit-cover me-3" />
                            <div class="w-100">
                                <router-link :to="'/instructor/' + course.instructor.id"
                                    class="text-decoration-none d-block">{{ course.instructor.name }}</router-link>
                                <router-link :to="'/details/' + course.id"
                                    class="text-decoration-none d-block text-hover fs-5 mb-1">{{
                                        shortTitle(course.title) }}</router-link>
                                <div :class="course.subscription ? 'mb-2' : 'mb-4'" >
                                    <small class="me-3">
                                        <i class="bi bi-clock text-success me-1"></i>
                                        {{
                                            formatDuration(
                                                course.total_duration
                                            )
                                        }}
                                    </small>
                                    <small class="me-3">
                                        <i class="bi bi-play-circle text-danger me-1"></i>
                                        {{ course.video_count }} {{ $t('Videos') }}
                                    </small>
                                </div>
                                <div class="d-flex">
                                    <div class="col-10 my-auto pe-3">
                                        <div class="progress" role="progressbar" aria-label="Basic example"
                                            :aria-valuenow="course.progress" aria-valuemin="0" aria-valuemax="100">
                                            <div class="progress-bar" :style="'width:' +
                                                course.progress +
                                                '%'
                                                "></div>
                                        </div>
                                    </div>
                                    <div class="col-2 my-auto text-end">
                                        <span class="text-primary">{{
                                            course.progress
                                                ? course.progress
                                                : 0
                                        }}%</span>
                                    </div>
                                </div>
                                <div v-if="course.subscription">
                                    <p :class="course.subscription?.status == true ? 'mt-0 mb-0' : 'text-danger mt-0 mb-0'" style="font-size: 12px;">Expires on {{ course.subscription?.ends_at }}</p>
                                </div>
                            </div>
                            <div v-if="course.subscription" class="subscribe-badge-position">
                                <i class="bi bi-star-fill text-warning"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <div v-if="inProgressCourses.length === 0 && courses.length === 0 && subscribedCourses.length === 0"
                    class="col-12">
                    <div class="text-center text-muted">
                        <i class="bi bi-emoji-frown display-5"></i>
                        <p class="mb-0 mt-3">{{ $t('No Course Available in Progress') }}</p>
                    </div>
                </div>
            </div>
            <div class="tab-pane fade" id="pills-regular" role="tabpanel" aria-labelledby="pills-regular-tab"
                tabindex="0">
                <div class="row">
                    <div v-for="course in inProgressCourses" :key="course.id" class="col-lg-4 mb-4">
                        <div class="bg-white theme-shadow rounded d-flex flex-column gap-2 p-3">
                            <img :src="course.thumbnail" alt="" height="140px" width="140px"
                                class="object-fit-cover me-3" />
                            <div class="w-100">
                                <router-link :to="'/instructor/' + course.instructor.id"
                                    class="text-decoration-none d-block">{{ course.instructor.name }}</router-link>
                                <router-link :to="'/details/' + course.id"
                                    class="text-decoration-none d-block text-hover fs-5 mb-1">{{
                                        shortTitle(course.title) }}</router-link>
                                <div class="mb-3">
                                    <small class="me-3">
                                        <i class="bi bi-clock text-success me-1"></i>
                                        {{
                                            formatDuration(
                                                course.total_duration
                                            )
                                        }}
                                    </small>
                                    <small class="me-3">
                                        <i class="bi bi-play-circle text-danger me-1"></i>
                                        {{ course.video_count }} {{ $t('Videos') }}
                                    </small>
                                </div>
                                <div class="d-flex">
                                    <div class="col-10 my-auto pe-3">
                                        <div class="progress" role="progressbar" aria-label="Basic example"
                                            :aria-valuenow="course.progress" aria-valuemin="0" aria-valuemax="100">
                                            <div class="progress-bar" :style="'width:' +
                                                course.progress +
                                                '%'
                                                "></div>
                                        </div>
                                    </div>
                                    <div class="col-2 my-auto text-end">
                                        <span class="text-primary">{{
                                            course.progress
                                                ? course.progress
                                                : 0
                                        }}%</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div v-if="inProgressCourses.length === 0" class="col-12">
                    <div class="text-center text-muted">
                        <i class="bi bi-emoji-frown display-5"></i>
                        <p class="mb-0 mt-3">{{ $t('No Course Available in Progress') }}</p>
                    </div>
                </div>
            </div>
            <div class="tab-pane fade" id="pills-completed" role="tabpanel" aria-labelledby="pills-completed-tab"
                tabindex="0">
                <div class="row">
                    <div v-for="course in completedCourses" :key="course.id" class="col-lg-4 mb-4">
                        <div class="bg-white theme-shadow rounded d-flex flex-column gap-2 p-3">
                            <img :src="course.thumbnail" alt="" height="140px" width="140px"
                                class="object-fit-cover me-3" />
                            <div class="w-100">
                                <router-link :to="'/profile/' + course.instructor.id"
                                    class="text-decoration-none d-block">{{ course.instructor.name }}</router-link>
                                <router-link :to="'/details/' + course.id"
                                    class="text-decoration-none d-block text-hover fs-5 mb-1">{{
                                        shortTitle(course.title) }}</router-link>
                                <div class="mb-3">
                                    <small class="me-3">
                                        <i class="bi bi-clock text-success me-1"></i>
                                        {{
                                            Math.floor(
                                                course.total_duration / 60
                                            )
                                        }}
                                        Hours
                                    </small>
                                    <small class="me-3">
                                        <i class="bi bi-play-circle text-danger me-1"></i>
                                        {{ course.video_count }} {{ $t('Videos') }}
                                    </small>
                                </div>
                                <div class="d-flex">
                                    <div class="col-10 my-auto pe-3">
                                        <div class="progress" role="progressbar" aria-label="Basic example"
                                            :aria-valuenow="course.progress" aria-valuemin="0" aria-valuemax="100">
                                            <div class="progress-bar" :style="'width:' +
                                                course.progress +
                                                '%'
                                                "></div>
                                        </div>
                                    </div>
                                    <div class="col-2 my-auto text-end">
                                        <span class="text-primary">{{
                                            course.progress
                                                ? course.progress
                                                : 0
                                        }}%</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div v-if="completedCourses.length === 0" class="col-12">
                    <div class="text-center text-muted">
                        <i class="bi bi-emoji-frown display-5"></i>
                        <p class="mb-0 mt-3">{{ $t('No Course Completed') }}</p>
                    </div>
                </div>
            </div>
            <div class="tab-pane fade" id="pills-subscribed" role="tabpanel" aria-labelledby="pills-subscribed-tab"
                tabindex="0">
                <div class="row">
                    <div v-for="course in subscribedCourses" :key="course.id" class="col-lg-4 mb-4">
                        <div class="bg-white theme-shadow rounded d-flex flex-column gap-2 p-3">
                            <img :src="course.thumbnail" alt="" height="140px" width="140px"
                                class="object-fit-cover me-3" />
                            <div class="w-100">
                                <router-link :to="'/profile/' + course.instructor.id"
                                    class="text-decoration-none d-block">{{ course.instructor.name }}</router-link>
                                <router-link :to="'/details/' + course.id"
                                    class="text-decoration-none d-block text-hover fs-5 mb-1">{{
                                        shortTitle(course.title) }}</router-link>
                                <div class="mb-3">
                                    <small class="me-3">
                                        <i class="bi bi-clock text-success me-1"></i>
                                        {{
                                            Math.floor(
                                                course.total_duration / 60
                                            )
                                        }}
                                        Hours
                                    </small>
                                    <small class="me-3">
                                        <i class="bi bi-play-circle text-danger me-1"></i>
                                        {{ course.video_count }} {{ $t('Videos') }}
                                    </small>
                                </div>
                                <div class="d-flex">
                                    <div class="col-10 my-auto pe-3">
                                        <div class="progress" role="progressbar" aria-label="Basic example"
                                            :aria-valuenow="course.progress" aria-valuemin="0" aria-valuemax="100">
                                            <div class="progress-bar" :style="'width:' +
                                                course.progress +
                                                '%'
                                                "></div>
                                        </div>
                                    </div>
                                    <div class="col-2 my-auto text-end">
                                        <span class="text-primary">{{
                                            course.progress
                                                ? course.progress
                                                : 0
                                        }}%</span>
                                    </div>
                                </div>
                            </div>
                            <div class="card border rounded p-3 shadow-sm">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <span class="badge bg-primary text-white rounded-pill px-3 py-2">
                                        {{ course.subscription.plan.title }}
                                    </span>
                                </div>
                                <div class="d-flex justify-content-between text-muted small mt-2 px-1">
                                    <div>
                                        <strong>Start Date:</strong> {{ course.subscription?.starts_at }}
                                    </div>
                                    <div :class="course.subscription?.status == true ? '' : 'text-danger'">
                                        <strong>End Date:</strong> {{ course.subscription?.ends_at }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div v-if="subscribedCourses.length === 0" class="col-12">
                    <div class="text-center text-muted">
                        <i class="bi bi-emoji-frown display-5"></i>
                        <p class="mb-0 mt-3">{{ $t('No Course Completed') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>

<style lang="scss" scoped>
.nav {
    .nav-link {
        color: #000;
        border: 2px solid #2D2727;
        border-radius: 5px;
        padding: 4px 0.75rem;
        font-weight: 500;
        font-size: 0.875rem;
        color: #2D2727;
        transition: background-color 0.3s ease;
    }

    .nav-link.active {
        background-color: #fff;
        color: #9e4aed;
        border: 2px solid #9e4aed;
        border-radius: 5px;
    }
}

.progress {
    height: 0.5rem;
}

.subscribe-badge {
    position: absolute;
    top: -15px;
    right: -15px;
    background-color: #fff;
    border-radius: 50%;
    width: 30px;
    height: 30px;
    display: flex;
    font-size: 14px;
    align-items: center;
    justify-content: center;
    box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
}
.subscribe-badge-position {
    position: absolute;
    top: -12px;
    right: 0px;
    background-color: #fff;
    border-radius: 50%;
    width: 30px;
    height: 30px;
    display: flex;
    font-size: 14px;
    align-items: center;
    justify-content: center;
    box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
}
</style>

<script setup>
import { useAuthStore } from "@/stores/auth";
import { ref } from "vue";
const authStore = useAuthStore();

let courses = ref({});
let inProgressCourses = ref({});
let completedCourses = ref({});
let subscribedCourses = ref({});

axios
    .get("/enrolled_courses", {
        headers: {
            "Content-Type": "application/json",
            Accept: "application/json",
            Authorization: "Bearer " + authStore.authToken,
        },
    })
    .then((res) => {
        // inProgressCourses.value = res.data.data.courses.filter((course) => {
        //     return course.progress < 100;
        // });
        // completedCourses.value = res.data.data.courses.filter((course) => {
        //     return course.progress === 100;
        // });

        courses.value = res.data.data.courses;
        inProgressCourses.value = res.data.data.regular_courses;
        completedCourses.value = res.data.data.completed_courses;
        subscribedCourses.value = res.data.data.subscribed_courses;
    });

function shortTitle(title) {
    return title.length > 30 ? title.slice(0, 30) + "..." : title;
}

// works on time formating

const formatDuration = (duration) => {
    if (duration >= 60) {
        const hours = Math.floor(duration / 60);
        const minutes = duration % 60;
        return `${hours} hour${hours > 1 ? "s" : ""}${minutes > 0 ? ` ${minutes} min` : ""
            }`;
    }
    return `${duration} min`;
};
</script>
