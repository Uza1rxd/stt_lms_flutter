import { createRouter, createWebHistory } from "vue-router";
import defaultLayout from "./layouts/Default.vue";
import authLayout from "./layouts/Auth.vue";
import { useAuthStore } from "./stores/auth";
import Blank from "./layouts/blank.vue";

const router = createRouter({
    history: createWebHistory(),
    routes: [
        {
            path: "/",
            name: "home",
            component: () => import("./pages/Home.vue"),
            meta: {
                layout: defaultLayout,
            },
        },
        {
            path: "/courses",
            name: "list",
            component: () => import("./pages/List.vue"),
            meta: {
                layout: defaultLayout,
            },
        },
        {
            path: "/details/:id",
            name: "details",
            component: () => import("./pages/Details.vue"),
            meta: {
                layout: defaultLayout,
            },
        },
        {
            path: "/play/:course_id",
            name: "play",
            component: () => import("./pages/Play.vue"),
            meta: {
                layout: defaultLayout,
            },
        },
        {
            path: "/instructor/:id",
            name: "instructor",
            component: () => import("./pages/Instructor.vue"),
            meta: {
                layout: defaultLayout,
            },
        },
        {
            path: "/login",
            name: "login",
            component: () => import("./pages/Login.vue"),
            meta: {
                layout: Blank,
            },
        },
        {
            path: "/register",
            name: "register",
            component: () => import("./pages/Register.vue"),
            meta: {
                layout: Blank,
            },
        },
        {
            path: "/reset_password",
            name: "reset_password",
            component: () => import("./pages/ResetPassword.vue"),
            meta: {
                layout: Blank,
            },
        },
        {
            path: "/verify_otp",
            name: "verify_otp",
            component: () => import("./pages/VerifyOtp.vue"),
            meta: {
                layout: Blank,
            },
        },
        {
            path: "/new_password",
            name: "new_password",
            component: () => import("./pages/NewPassword.vue"),
            meta: {
                layout: Blank,
            },
        },
        {
            path: "/checkout/:id",
            name: "checkout",
            component: () => import("./pages/Checkout.vue"),
            meta: {
                layout: Blank,
                requiresAuth: true,
            },
        },
        {
            path: "/plan-checkout",
            name: "plan_checkout",
            component: () => import("./pages/PlanCheckout.vue"),
            meta: {
                layout: defaultLayout,
                requiresAuth: true,
            },
        },
        {
            path: "/enroll_status",
            name: "enroll_status",
            component: () => import("./pages/EnrollStatus.vue"),
            meta: {
                layout: Blank,
            },
        },
        {
            path: "/plan_enroll_status",
            name: "plan_enroll_status",
            component: () => import("./pages/PlanEnrollStatus.vue"),
            meta: {
                layout: Blank,
            },
        },
        {
            path: "/dashboard",
            name: "dashboard",
            component: () => import("./components/DashboardHome.vue"),
            meta: {
                layout: authLayout,
                requiresAuth: true,
            },
        },
        {
            path: "/dashboard/profile",
            name: "myProfile",
            component: () => import("./components/DashboardProfile.vue"),
            meta: {
                layout: authLayout,
                requiresAuth: true,
            },
        },
        {
            path: "/dashboard/courses",
            name: "myCourse",
            component: () => import("./components/DashboardCourses.vue"),
            meta: {
                layout: authLayout,
                requiresAuth: true,
            },
        },
        {
            path: "/dashboard/certificate",
            name: "myCertificate",
            component: () => import("./components/DashboardCertificates.vue"),
            meta: {
                layout: authLayout,
                requiresAuth: true,
            },
        },
        {
            path: "/dashboard/payment-history",
            name: "myPaymentHistory",
            component: () => import("./components/DashboardPayment.vue"),
            meta: {
                layout: authLayout,
                requiresAuth: true,
            },
        },
        {
            path: "/dashboard/plan-renewal-history",
            name: "myPlanPaymentHistory",
            component: () => import("./components/DashboardPlanPayment.vue"),
            meta: {
                layout: authLayout,
                requiresAuth: true,
            },
        },
        {
            path: "/page/:slug",
            name: "page",
            component: () => import("./pages/Page.vue"),
            meta: {
                layout: defaultLayout,
            },
        },
        {
            path: "/about-us",
            name: "about_us",
            component: () => import("./pages/AboutUs.vue"),
            meta: {
                layout: defaultLayout,
            },
        },
        {
            path: "/exam/:id",
            name: "exam",
            component: () => import("./pages/Exam.vue"),
            meta: {
                layout: defaultLayout,
            },
        },
        {
            path: "/quiz/:id",
            name: "quiz",
            component: () => import("./pages/Quiz.vue"),
            meta: {
                layout: defaultLayout,
            },
        },
        {
            path: "/contact-us",
            name: "contact_us",
            component: () => import("./pages/ContactUs.vue"),
            meta: {
                layout: defaultLayout,
            },
        },
        {
            path: "/faq",
            name: "faq",
            component: () => import("./pages/FAQ.vue"),
            meta: {
                layout: defaultLayout,
            },
        },
        {
            path: "/:catchAll(.*)",
            name: "notFound",
            component: () => import("./pages/PageNotFound.vue"),
            meta: {
                layout: Blank,
            },
        },
    ],
});

router.beforeEach((to, from, next) => {
    const authStore = useAuthStore(); // Get auth store instance

    if (to.meta.requiresAuth && !authStore.userData) {
        return next({ name: "login" });
    } else if (
        (to.name === "login" || to.name === "register") &&
        authStore.userData
    ) {
        return next({ name: "home" });
    } else if (
        from.name === "checkout" &&
        authStore.userData &&
        to.name !== "details" &&
        to.name !== "enroll_status"
    ) {
        return next({ name: "details", params: { id: to.params.id } });
    }

    next(); // Proceed to the next route
});

export default router;
