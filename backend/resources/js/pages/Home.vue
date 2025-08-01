<template>
    <Hero />
    <section class="pb-5 pt-0 pt-lg-5">
        <div class="container py-0 py-lg-0">
            <About />
        </div>
    </section>

    <section class="py-3">
        <div class="container py-0 py-lg-2">
            <h3 class="fw-bold text-center fs-2 mb-4">
                {{ $t('Explore Our Popular Courses') }}
            </h3>
            <PopularCourses />
        </div>
    </section>

    <section class="py-3 border-bottom">
        <div class="container py-0 py-lg-2">
            <FeaturedCategories />
        </div>
    </section>

    <section class="py-3 border-bottom">
        <div class="container py-0 py-lg-2">
            <div class="d-flex justify-content-between align-items-center mb-5">
                <div>
                    <h3 class="fw-bold fs-2">{{ $t('Newest Courses') }}</h3>
                    <p>{{ $t('Recently published courses') }}</p>
                </div>
                <router-link to="/courses" class="text-primary text-decoration-none fw-bold">{{ $t('View All') }}
                    <font-awesome-icon :icon="faArrowRightLong" /></router-link>
            </div>
            <LatestCourseSlider />
        </div>
    </section>

    <section v-if="masterStore?.masterData?.publish_plan" class="py-4">
        <div class="container py-0">
            <div class="d-flex justify-content-center align-items-center text-center mb-3">
                <div>
                    <h2 class="fw-bold fs-2">{{ $t('Our') }} <spam class="text-primary">{{ $t('Pricing') }}</spam> {{
                        $t('Plans') }}</h2>
                    <p>{{ $t('Choose from affordable plans to advance on your learning journey') }}</p>
                </div>
            </div>
            <Plans />
        </div>
    </section>

    <section class="py-3">
        <div class="container py-0 py-lg-2">
            <div class="row">
                <div class="col-12">
                    <h3 class="fw-bold text-start fs-2 mb-3">
                        {{ $t('Top Rated') }} <span class="text-primary">{{ $t('Instructors') }}</span>
                    </h3>
                    <p>{{ $t('Quality Education Starts with Great Instructors') }}</p>
                </div>
            </div>
            <FeaturedInstructors />
        </div>
    </section>

    <section class="py-3 py-lg-3 bg-light-purple border-top border-bottom">
        <div class="container py-3">
            <div class="row">
                <div class="col-12">
                    <h3 class="fw-bold text-center fs-2 mb-5">
                        {{ $t('Hear from Our Students and Instructors') }}
                    </h3>
                </div>
            </div>
            <Testimonials />
        </div>
    </section>

    <section class="pt-4 pb-0">
        <div class="container py-0 py-lg-3">
            <FAQ />
        </div>
    </section>

    <section class="py-0 py-lg-3">
        <div class="container py-3">
            <BecomeInstructor />
        </div>
    </section>

    <section class="pt-0 pt-lg-2">
        <div class="container">
            <Newsletter />
        </div>
    </section>

    // popup offer component
    <OfferModal />
</template>


<script setup>
import { useMasterStore } from "@/stores/master";
const masterStore = useMasterStore();

import { FontAwesomeIcon } from "@fortawesome/vue-fontawesome";
import { faArrowRightLong } from "@fortawesome/free-solid-svg-icons";
import Hero from "../components/Hero.vue";
import About from "../components/About.vue";
import FeaturedCategories from "../components/FeaturedCategories.vue";
import FeaturedInstructors from "../components/FeaturedInstructors.vue";
import FAQ from "../components/FAQ.vue";
import Testimonials from "../components/Testimonials.vue";
import BecomeInstructor from "../components/BecomeInstructor.vue";
import Newsletter from "../components/Newsletter.vue";
import PopularCourses from "../components/PopularCourses.vue";
import LatestCourseSlider from "../components/LatestCourseSlider.vue";
import Plans from "../components/Plans.vue";
import OfferModal from "../components/OfferModal.vue";
</script>
