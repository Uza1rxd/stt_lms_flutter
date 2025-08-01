<template>
    <section class="my-2">
        <span class="d-block mb-2 mb-lg-3 fs-4 fw-bold">{{ $t('Home') }}/{{ $t('Dashboard') }}</span>

        <div class="row mb-4 bg-white rounded-3 py-3 px-1">
            <div class="col-md-6 col-lg-4 mb-4 mb-lg-0">
                <div class="border border-primary rounded p-3 d-flex justify-content-between h-100">
                    <div class="my-auto">
                        <strong class="fs-5">{{ totalCourseCount }}</strong>
                        <span class="d-block">{{ $t('My Courses') }}</span>
                    </div>
                    <img :src="'assets/images/website/top-1.svg'" height="60px" width="60px" class="my-auto" />
                </div>
            </div>

            <div class="col-md-6 col-lg-4 mb-4 mb-lg-0">
                <div class="border border-primary rounded p-3 d-flex justify-content-between h-100">
                    <div class="my-auto">
                        <strong class="fs-5">{{ completedCourseCount }}</strong>
                        <span class="d-block">{{ $t('Completed Courses') }}</span>
                    </div>
                    <img :src="'assets/images/website/top-2.svg'" height="60px" width="60px" class="my-auto" />
                </div>
            </div>

            <div class="col-md-6 col-lg-4 mb-4 mb-lg-0 mx-auto mx-lg-0">
                <div class="border border-primary rounded p-3 d-flex justify-content-between h-100">
                    <div class="my-auto">
                        <strong class="fs-5">{{ certificateAchieved }}</strong>
                        <span class="d-block">
                            {{ $t('Certificate Achieved') }}
                        </span>
                    </div>
                    <img :src="'assets/images/website/top-3.svg'" height="60px" width="60px" class="my-auto" />
                </div>
            </div>
        </div>

        <div v-for="schedule in classSchedules" :key="schedule.id"
            class="alert alert-info alert-dismissible fade show mb-4" role="alert">
            <strong>{{ $t('Hello') }} {{ authStore.userData?.name }}!</strong> {{ $t('Please start your class at') }} {{
                schedule.start_time }}
            {{ $t('and join the meeting via this link') }}:
            <a :href="schedule?.class_link" target="_blank" class="text-decoration-none text-primary">
                {{ schedule?.class_link }}
            </a>.
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>

        <div class="row mb-4">
            <div class="col-12 col-lg-6 mb-3 mb-lg-0">
                <div class="border course-preview-wrapper bg-white py-3 px-2 rounded-3">
                    <h4 class="mb-4 ms-2">{{ $t('Recent Lesson') }}</h4>
                    <div v-if="lastActivityCourse" class="course-preview" :style="'background-image: url(' +
                        lastActivityCourse?.thumbnail +
                        ');'
                        ">
                        <router-link :to="'/play/' + lastActivityCourse?.id"
                            class="play-btn rounded-circle bg-primary text-white">
                            <PlayIcon class="play-icon"></PlayIcon>
                        </router-link>
                    </div>
                    <div v-if="lastActivityCourse" class="p-3">
                        <router-link :to="`/details/${lastActivityCourse?.id}`" class="text-decoration-none text-hover">
                            <h5 class="card-title mb-3 text-center text-wrap">
                                {{ lastActivityCourse?.title }}
                            </h5>
                        </router-link>
                        <router-link :to="'/play/' + lastActivityCourse.id"
                            class="text-decoration-none text-muted d-flex justify-content-between border rounded px-3 py-2">
                            <div>
                                <i class="bi bi-play-circle-fill text-danger me-2"></i>
                                <small>{{
                                    lastActivityCourse?.title?.length > 30
                                        ? lastActivityCourse?.title.slice(
                                            0,
                                            30
                                        ) + "..."
                                        : lastActivityCourse?.title
                                }}</small>
                            </div>
                            <small>{{
                                formatDuration(lastActivityCourse.total_duration)
                                }}</small>
                        </router-link>
                    </div>
                    <div v-else>
                        <p class="text-danger text-center border rounded px-3 py-2">
                            {{ $t('No recent lesson found') }}
                        </p>
                    </div>
                </div>
            </div>

            <div class="col-12 col-lg-6 d-flex d-lg-block justify-content-center border bg-white py-3 px-2 rounded-3">
                <h4 class="mb-4 ms-2">{{ $t('Activity Log') }}</h4>
                <div v-if="totalCourseCount > 0">
                    <VueApexCharts type="donut" :options="chartOptions" :series="series" height="400px" />
                </div>
                <div v-else>
                    <p class="text-danger text-center border rounded px-3 py-2">{{ $t('No activity history found') }}
                    </p>
                </div>
            </div>
        </div>
    </section>
</template>


<style lang="scss" scoped>
.course-preview {
    height: 300px;
    background-size: contain;
    background-position: center;
    background-repeat: no-repeat;
    border-top-left-radius: 1rem;
    border-top-right-radius: 1rem;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #fff;
    font-size: 2rem;
    font-weight: bold;

    .play-btn {
        cursor: pointer;
        width: 50px;
        height: 50px;
        display: flex;
        align-items: center;
        justify-content: center;
        border: 5px solid #ffffffbd;
        border-radius: 50%;
        font-size: 16px;
    }
}

.course-preview-wrapper {
    border-radius: 1rem;
}

.play-icon {
    width: 24px;
    height: 24px;
}
</style>

<script setup>
import VueApexCharts from "vue3-apexcharts";
import { useAuthStore } from "@/stores/auth";
import { ref } from "vue";
import { PlayIcon } from "@heroicons/vue/24/solid";

const authStore = useAuthStore();

let totalCourseCount = ref(0);
let completedCourseCount = ref(0);
let certificateAchieved = ref(0);
let lastActivityCourse = ref({});

let series = ref([]);

let chartOptions = ref({
    // series: series.value,
    chart: {
        type: "donut",
    },
    labels: ["Total Courses", "Completed Courses", "Certificates Achieved"],
    responsive: [
        {
            breakpoint: 480,
            options: {
                chart: {
                    width: 200,
                },
                legend: {
                    position: "bottom",
                },
            },
        },
    ],
});

axios
    .get("/enroll_summary", {
        headers: {
            "Content-Type": "application/json",
            Accept: "application/json",
            Authorization: "Bearer " + authStore.authToken,
        },
    })
    .then((res) => {
        (totalCourseCount.value = res.data.data.total_courses),
            (completedCourseCount.value = res.data.data.completed_courses),
            (certificateAchieved.value = res.data.data.certificate_achieved),
            (lastActivityCourse.value = res.data.data.last_activity_course);

        series.value = [
            totalCourseCount.value,
            completedCourseCount.value,
            certificateAchieved.value,
        ];
    });

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
