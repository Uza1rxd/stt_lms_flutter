<template>
    <div v-if="loading" class="text-center py-5">
        <div v-if="loading" class="fullpage-skeleton bg-white">
            <PlanCheckoutSkeleton />
        </div>
    </div>

    <div v-else class="container py-3">
        <div class="row g-4 d-flex align-items-stretch">
            <!-- Left: Course Selection -->
            <div class="col-lg-6">
                <div class="card shadow-sm rounded-4">
                    <div class="card-body">
                        <h4 class="mb-4 text-primary">{{ $t('Select Subscription Plan') }}</h4>

                        <select class="form-select mb-4" v-model="selectedPlan">
                            <option disabled value="">{{ $t('Choose Plan') }}</option>
                            <option v-for="plan in plans" :key="plan.id" :value="plan"
                                :selected="plan.id == $route.query.id ? true : false">
                                {{ plan.title }} ({{ $t('Max') }} {{ plan.course_limit }} {{ $t('courses') }})
                            </option>
                        </select>

                        <h4 class="mb-3">{{ $t('Select Courses') }}</h4>

                        <div v-if="courseLimit !== null && courses.length > 0" class="course-scroll-container">
                            <div v-for="course in courses" :key="course.id" :class="[
                                'card mb-3 shadow-sm border-0 rounded-4',
                                (courseLimit !== null && selectedCourses.length >= courseLimit && !selectedCourses.some(c => c.id === course.id))
                                    ? 'disabled-course' : ''
                            ]">
                                <div class="row g-0 align-items-center">
                                    <div class="col-md-4" @click="toggleCourseSelection(course)">
                                        <img :src="course.thumbnail"
                                            class="img-fluid rounded-start w-100 h-100 object-fit-cover"
                                            alt="Course Thumbnail" />
                                    </div>
                                    <div class="col-md-8" @click="toggleCourseSelection(course)">
                                        <div class="card-body">
                                            <div class="d-flex justify-content-between">
                                                <h5 class="card-title mb-1">{{ course.title }}</h5>
                                                <div class="form-check ms-2" @click="toggleCourseSelection(course)">
                                                    <input class="form-check-input" type="checkbox"
                                                        :id="'course-' + course.id" :value="course"
                                                        :disabled="courseLimit !== null && selectedCourses.length >= courseLimit && !selectedCourses.some(c => c.id === course.id)"
                                                        v-model="selectedCourses" style="width: 20px; height: 20px;" />
                                                </div>
                                            </div>
                                            <p class="card-text text-muted mb-1">{{ course.instructor.user.name }}
                                            </p>
                                            <p class="card-text fw-bold text-primary mb-0">
                                                {{ masterStore?.masterData?.currency_symbol }}{{ course.price ?
                                                    course.price : course.regular_price }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div v-if="courses.length === 0" class="course-skeletons">
                                <div v-for="n in 3" :key="n" class="card mb-3 shadow-sm border-0 rounded-4">
                                    <div class="row g-0">
                                        <div class="col-md-4">
                                            <div class="placeholder-glow w-100 h-100"
                                                style="height: 120px; background-color: #e0e0e0;">

                                            </div>
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

                        <div v-if="error" class="alert alert-danger mt-3">
                            {{ error }}
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right: Payment Section -->
            <div class="col-lg-6">
                <div class="card shadow-sm rounded-4 mb-3">
                    <div class="card-body">
                        <h4 class="mb-4 text-primary">{{ $t('Payment Details') }}</h4>

                        <div>
                            <ul v-if="selectedCourses.length > 0" class="list-group mb-3 shadow-sm">
                                <li v-for="(course, index) in selectedCourses" :key="course.id"
                                    class="list-group-item d-flex justify-content-between align-items-center">
                                    <span>
                                        #{{ index + 1 }}
                                        <img :src="course.thumbnail" class="me-1 ms-1 course-thumbnail" alt="..." />
                                        {{ course.title.slice(0, 40) + (course.title.length > 40 ? '...' : '') }}
                                    </span>
                                    <strong class="text-muted">
                                        {{ masterStore?.masterData?.currency_symbol }}{{ course.price ? course.price :
                                            course.regular_price }}
                                    </strong>
                                </li>
                                <li class="list-group-item d-flex justify-content-between">
                                    <strong>{{ $t('Total Price Without Plan') }}</strong>
                                    <strong class="text-primary">
                                        {{ masterStore?.masterData?.currency_symbol }}{{ totalPrice }}
                                    </strong>
                                </li>
                                <li class="list-group-item d-flex justify-content-between">
                                    <strong>{{ $t('Subscription Plan Price') }}</strong>
                                    <strong class="text-primary">
                                        {{ masterStore?.masterData?.currency_symbol }}{{ selectedPlan?.price }}
                                    </strong>
                                </li>
                                <li v-if="selectedCourses.length >= courseLimit"
                                    class="list-group-item d-flex justify-content-center">
                                    <strong class="text-center text-muted">
                                        {{ $t('You save') }} <span class="text-primary">
                                            {{ masterStore?.masterData?.currency_symbol + savedAmount }}
                                        </span> – {{ $t("that’s mean") }}
                                        <span class="text-primary">{{ savePercent }}%</span> {{ $t('off') }}!
                                    </strong>
                                </li>
                            </ul>

                            <ul v-else class="text-muted list-group mb-3">
                                <li class="list-group-item d-flex justify-content-between align-items-center">{{
                                    $t('Please select at least one course to proceed with payment') }}.</li>
                            </ul>

                        </div>

                    </div>
                </div>
                <div class="card shadow-sm rounded-4">
                    <div class="card-body">
                        <div class="d-flex flex-column">
                            <div class="mb-3">
                                <div class="d-flex justify-content-between align-items-center mb-4">
                                    <h5 class="fw-bold">{{ $t('Payment Method') }}</h5>
                                    <small class="bg-lightgreen text-success rounded px-2 py-1 text-center">
                                        <i class="ri-shield-check-fill me-1"></i>
                                        {{ $t('100% Secure Payment') }}
                                    </small>
                                </div>
                                <div class="payment-method-container">
                                    <div v-for="gateway in masterStore?.masterData?.payment_methods" :key="gateway.id">
                                        <div @click="selectedPaymentMethod = gateway.gateway" :class="'border d-flex justify-content-between align-items-center px-3 mb-3 rounded payment-method'
                                            + (selectedPaymentMethod == gateway.gateway ? ' selected' : '')">
                                            <img :src="gateway.logo" class="py-3 payment-logo" />
                                            <input class="form-check-input" type="radio" :checked="selectedPaymentMethod ==
                                                gateway.gateway" />
                                        </div>
                                    </div>
                                </div>

                            </div>

                            <div class="border-top p-4 d-flex align-items-start gap-3">
                                <input type="checkbox" class="form-check-input" id="exampleCheck1" required
                                    @change="acceptTerms = true" />
                                <label class="form-check-label" for="exampleCheck1">
                                    {{ $t('By proceeding, you agree to the') }}
                                    <router-link to="/page/terms_and_conditions"
                                        class="text-decoration-none bg-transparent border-0 text-primary">
                                        {{ $t('Terms & Condition') }}</router-link>,<router-link
                                        to="/page/privacy_policy"
                                        class="text-decoration-none bg-transparent border-0 text-primary">
                                        {{ $t('Privacy Policy') }}</router-link>
                                    {{ $t('and') }}
                                    <router-link to="/page/refund_policy"
                                        class="text-decoration-none bg-transparent border-0 text-primary">
                                        {{ $t('Refund Policy') }}</router-link> of {{ masterStore?.masterData?.name
                                        }}
                                </label>
                            </div>

                            <div class="mt-auto border-top p-4">
                                <button @click="handlePayment" class="btn btn-primary rounded-pill fw-bold w-100">
                                    {{ $t('Pay') }} {{ masterStore?.masterData?.currency_symbol
                                    }}{{ selectedPlan?.price }}
                                    <i class="bi bi-arrow-right float-end"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>
.fullpage-skeleton {
    position: fixed;
    inset: 0;
    z-index: 9999;
    background-color: #fff;
    overflow-y: auto;
}

.course-scroll-container {
    max-height: 500px;
    overflow-y: auto;
    padding-right: 5px;
    scrollbar-width: thin;
    scrollbar-color: #9e4aed #f1f1f1;
}

.course-scroll-container::-webkit-scrollbar {
    width: 8px;
}

.course-scroll-container::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 5px;
}

.course-scroll-container::-webkit-scrollbar-thumb {
    background-color: #9e4aed;
    border-radius: 5px;
}

.course-scroll-container::-webkit-scrollbar-thumb:hover {
    background-color: #9e4aed;
}

.disabled-course {
    opacity: 0.5;
    pointer-events: none;
}

.disabled-course input[type="checkbox"] {
    pointer-events: auto;
}

.course-thumbnail {
    width: 50px;
    height: 50px;
    object-fit: cover;
    border-radius: 5px;
}

.payment-method {
    margin-right: 12px;
    cursor: pointer;

    &:hover {
        border-color: #9e4aed !important;
    }
}

.payment-method.selected {
    border-color: #9e4aed !important;
}

.payment-logo {
    max-width: 100px;
    max-height: 80px;
    object-fit: contain;
}

.payment-method-container {
    max-height: 190px;
    overflow-y: auto;
    scrollbar-width: thin;
    scrollbar-color: #9e4aed #f1f1f1;
}
</style>

<script setup>
import { ref, computed, watch, onMounted, nextTick } from 'vue';
import PlanCheckoutSkeleton from '../components/PlanCheckoutSkeleton.vue';
import { useRoute } from 'vue-router';
import axios from 'axios';
import { useMasterStore } from '../stores/master';
import { useAuthStore } from '../stores/auth';
import Swal from 'sweetalert2';

// Reactive State
const route = useRoute();
const masterStore = useMasterStore();
const authStore = useAuthStore();
const courseId = route.query.id;
const selectedPaymentMethod = ref("paypal");
let acceptTerms = ref(false);
const courses = ref([]);
const plans = ref({});
const selectedPlan = ref('');
const selectedCourses = ref([]);
const error = ref('');
const loading = ref(true);
const courseLimit = ref(0);

const totalPrice = computed(() =>
    selectedCourses.value.reduce((sum, course) => sum + course.price, 0).toFixed(2)
);

const savedAmount = computed(() => {
    if (!selectedPlan.value?.price) return 0;
    return (totalPrice.value - selectedPlan.value.price).toFixed(2);
});

const savePercent = computed(() => {
    if (!totalPrice.value) return 0;
    return Math.round((savedAmount.value / totalPrice.value) * 100);
});

const handlePayment = () => {
    if (!selectedPlan.value) {
        error.value = 'Please select a plan first.';
        return;
    }

    if (acceptTerms.value && selectedPaymentMethod.value && selectedPlan.value && selectedCourses.value.length > 0) {
        console.log(`Selected Plan: ${selectedPlan.value.title}, Payment Method: ${selectedPaymentMethod.value}`);
        initiateTransaction();
    } else if (selectedCourses.value.length === 0) {
        const Toast = Swal.mixin({
            toast: true,
            position: "top-end",
            showConfirmButton: false,
            timer: 2000,
            timerProgressBar: true,
            didOpen: (toast) => {
                toast.onmouseenter = Swal.stopTimer;
                toast.onmouseleave = Swal.resumeTimer;
            },
        });
        Toast.fire({
            icon: "error",
            title: "Oops...",
            text: "Please select a course to proceed with payment.",
        });
    } else if (!acceptTerms.value) {
        const Toast = Swal.mixin({
            toast: true,
            position: "top-end",
            showConfirmButton: false,
            timer: 2000,
            timerProgressBar: true,
            didOpen: (toast) => {
                toast.onmouseenter = Swal.stopTimer;
                toast.onmouseleave = Swal.resumeTimer;
            },
        });
        Toast.fire({
            icon: "error",
            title: "Oops...",
            text: "Please select a payment method and agree to the terms and conditions",
        });
    }
};

// Fetch courses and plans dynamically
const fetchPlans = async () => {
    try {
        loading.value = true;
        const response = await axios.get(`/plan/list`);

        plans.value = response.data.data;
        const match = plans.value.find(p => p.id == route.query.id);
        if (match) {
            selectedPlan.value = match; await fetchPlanData(match.id);
        } else {
            selectedPlan.value = plans.value[0]; await fetchPlanData(plans.value[0].id);
        }

    } catch (err) {
        console.error(err);
        error.value = 'Failed to load course data.';
    } finally {
        loading.value = false;
    }
};

watch(selectedPlan, (newPlan) => {
    if (newPlan && newPlan.id) {
        selectedCourses.value = [];
        fetchPlanData(newPlan.id);
    }
});


const fetchPlanData = async (planId = null) => {
    try {
        loading.value = true;
        const response = await axios.get(`/plan/show/${planId}`);
        courses.value = response.data.data.courses || [];
        courseLimit.value = parseInt(response.data.data.course_limit) || 0;

    } catch (err) {
        console.error(err);
        error.value = 'Failed to load course data.';
    } finally {
        loading.value = false;
    }
};

const initiateTransaction = async () => {

    if (selectedPaymentMethod.value == "stripe" || selectedPaymentMethod.value == "paypal" || selectedPaymentMethod.value == "razorpay") {

        if (masterStore?.masterData?.currency != "USD" && selectedPlan.value.price < masterStore?.masterData?.minimum_amount) {
            const Toast = Swal.mixin({
                toast: true,
                position: "top-end",
                showConfirmButton: false,
                timer: 1500,
                timerProgressBar: true,
                didOpen: (toast) => {
                    toast.onmouseenter = Swal.stopTimer;
                    toast.onmouseleave = Swal.resumeTimer;
                },
            });
            Toast.fire({
                icon: "error",
                title: "Oops...",
                text: `Please note that a minimum of grand total of ${masterStore?.masterData?.minimum_amount} is required for transaction.`,
            });
            return;
        }

        if (masterStore?.masterData?.currency == "BDT") {
            const Toast = Swal.mixin({
                toast: true,
                position: "top-end",
                showConfirmButton: false,
                timer: 1500,
                timerProgressBar: true,
                didOpen: (toast) => {
                    toast.onmouseenter = Swal.stopTimer;
                    toast.onmouseleave = Swal.resumeTimer;
                },
            });
            Toast.fire({
                icon: "error",
                title: "Oops...",
                text: `BDT is not supported at the moment`,
            });
            return;
        }
    }

    try {
        const response = await axios.get(`/plan-enroll`, {
            headers: {
                "Content-Type": "application/json",
                Accept: "application/json",
                Authorization: `Bearer ${authStore.authToken}`,
            },
            params: {
                payment_gateway: selectedPaymentMethod.value,
                total_amount: selectedPlan.value.price,
                plan_id: selectedPlan.value.id,
                course_ids: selectedCourses.value ? selectedCourses.value.map((course) => course.id) : []
            },
        });
        openPaymentPopupWindow(response.data.data.payment_webview_url);
    } catch (error) {
        Swal.fire({
            icon: "error",
            title: "Oops...",
            text:
                error.response?.data?.message ||
                "Something went wrong. Please try again.",
        });
    }
};

const openPaymentPopupWindow = (url, debug = false) => {
    if (debug) console.log(`Opening payment window: ${url}`);

    const winWidth = 700;
    const winHeight = 700;
    const left = screen.width / 2 - winWidth / 2;
    const top = screen.height / 2 - winHeight / 2;

    const options = `resizable,height=${winHeight},width=${winWidth},top=${top},left=${left}`;
    const win = window.open(url, "_blank", options);
    if (!win) {
        if (debug) console.error("Failed to open payment window");
        return;
    }

    win.document.title = "Payment Window Screen - Make Payment";

    let intervalID = null;

    const handleWindowClose = (status) => {
        if (debug) console.log(`Closing with status: ${status}`);
        if (win && !win.closed) {
            win.close();
        }
        if (window.location.pathname !== "/plan_enroll_status") {
            window.location.href = `/plan_enroll_status?status=${status}`;
        }
    };

    const trackWindow = () => {
        try {
            if (win.closed) {
                if (debug) console.log("Window manually closed");
                clearInterval(intervalID);
                handleWindowClose("closed");
                return;
            }

            // Only do tracking if same-origin
            const currentHost = location.host;
            if (win.location.host === currentHost) {
                const pathname = win.location.pathname;
                if (pathname.includes("payment/success")) {
                    clearInterval(intervalID);
                    handleWindowClose("success");
                } else if (pathname.includes("payment/cancel")) {
                    clearInterval(intervalID);
                    handleWindowClose("cancel");
                } else if (pathname.includes("payment/fail")) {
                    clearInterval(intervalID);
                    handleWindowClose("fail");
                } else {
                    clearInterval(intervalID);
                    handleWindowClose("fail");
                }
            }

        } catch (e) {
            if (debug) console.warn("Cross-origin access blocked; cannot track status until redirect returns to same-origin.");
        }
    };

    intervalID = setInterval(trackWindow, 10);

    win.focus();
};



const toggleCourseSelection = (course) => {
    const checkbox = document.getElementById(`course-${course.id}`);
    if (!checkbox) return;

    const isChecked = checkbox.checked = !checkbox.checked;

    const alreadySelected = selectedCourses.value.some(c => c.id === course.id);

    if (isChecked && !alreadySelected) {
        selectedCourses.value.push(course);
    } else if (!isChecked && alreadySelected) {
        selectedCourses.value = selectedCourses.value.filter((c) => c.id !== course.id);
    }
};

// Lifecycle
onMounted(async () => {
    await fetchPlans();
    await nextTick();

    const courseId = route.query.course_id;

    if (courseId) {
        const course = courses.value.find(c => c.id == courseId);
        if (course) {
            toggleCourseSelection(course);
        }
    }

    const courseIds = route.query.course_ids
        ? route.query.course_ids
            .split(',')
            .map(id => parseInt(id.trim()))
            .filter(id => !isNaN(id))
        : [];

    if (courseIds.length > 0) {
        // Filter courses that match the provided course_ids
        const preSelectedCourses = courses.value.filter(course => courseIds.includes(course.id));

        if (preSelectedCourses.length > courseLimit.value) {
            const Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 2000,
                timerProgressBar: true,
                didOpen: (toast) => {
                    toast.onmouseenter = Swal.stopTimer;
                    toast.onmouseleave = Swal.resumeTimer;
                },
            });
            Toast.fire({
                icon: 'error',
                title: 'Oops...',
                text: `Cannot select ${preSelectedCourses.length} courses. The plan allows a maximum of ${courseLimit.value} courses.`,
            });
        } else {
            preSelectedCourses.forEach(course => {
                toggleCourseSelection(course);
            });
        }
    }


});
</script>
