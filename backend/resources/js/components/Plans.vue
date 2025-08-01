<template>
    <div id="subscriptionPlans" class="row bg-light-primary m-0 rounded-4">
        <div v-for="(plan, index) in plans" :key="plan.id" class="col-12 col-md-6 col-lg-4 pb-3 mb-md-0 plan-wrapper">
            <div class="card shadow-sm border-0 rounded-4 p-0 p-md-3" style="background: #ffffff !important;">
                <div class="card-body text-start">
                    <h5 class="card-title fw-bold">{{ plan.title }}</h5>
                    <p class="text-muted small card-text" v-html="plan.description">
                    </p>

                    <h2 class="fw-bold mb-3 plan-price">{{ setCurrency(plan.price) }}
                        <sup class="fs-6 fw-normal text-muted text-capitalize"> / {{ plan.plan_type }}</sup>
                    </h2>

                    <ul class="list-unstyled d-flex flex-column gap-3 text-start pt-3">
                        <li class=" allow">
                            <div class="feature-icon">
                                <i class="bi bi-check2 d-flex align-items-center me-2 custom-border"></i>
                            </div>
                            <span class="feature-text">
                                {{ $t('Select') }} {{ plan.course_limit }} {{ $t('courses of your choice') }}
                            </span>
                        </li>
                        <li class=" allow">
                            <div class="feature-icon">
                                <i class="bi bi-check2 d-flex align-items-center me-2 custom-border"></i>
                            </div>
                            <span class="feature-text">
                                {{ $t('Enjoy')  }}
                                {{ plan.plan_type == 'monthly' ? plan.duration + ' ' + 'days' : plan.duration + ' '
                                    + 'year' }}
                                {{ $t('of learning') }}
                            </span>
                        </li>
                        <li v-for="(feature, index) in plan.features" :key="index" class="allow">
                            <div class="feature-icon">
                                <i class="bi bi-check2 d-flex align-items-center custom-border"></i>
                            </div>
                            <span class="feature-text">
                                {{ feature }}
                            </span>
                        </li>
                    </ul>
                </div>
                <div class="mt-auto">
                    <button :id="plan_type" type="button" class="btn btn-outline-dark w-100"
                        @click="checkout(plan.id)" style="font-size: 18px;"><span class="fw-bold">{{  $t('Purchase Now')  }}</span>
                    </button>
                </div>
            </div>
        </div>

        <!-- end -->
    </div>
</template>

<style scoped>
#subscriptionPlans {
    padding: 32px 22px;
}

.card {
    background-color: #f5faff;
    border-radius: 1rem;
    display: flex;
    flex-direction: column;
    height: 100%;
}

.card-active {
    background-color: #1E293B !important;
}

.allow {
    color: #9e4aed;
    font-size: 14px;
    display: flex;
    align-items: center;
    gap: 4px;
    font-weight: 500;
}

.allow .custom-border {
    border-radius: 50%;
    width: 24px;
    height: 24px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: #9e4aed46;
}

.allow .feature-icon {
    width: 10%;
}

.allow .feature-text {
    width: 90%;
}

.allow span {
    font-size: 16px;
    color: #191D23;
}

.disallow {
    color: #191D23;
    font-size: 14px;
    display: flex;
    align-items: center;
    font-weight: 500;
}

.disallow .custom-border {
    border-radius: 50%;
    width: 24px;
    height: 24px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: #F7F8F9;
}

.disallow span {
    font-size: 16px;
    color: #A0ABBB;
}


.card-title {
    color: #191D23;
    font-family: 'Lexend', sans-serif;
    font-size: 24px;
    font-style: normal;
    font-weight: 500;
    line-height: 32px;
}

.card-text {
    color: #64748B;
    font-family: 'Lexend', sans-serif;
    font-size: 16px;
    font-style: normal;
    font-weight: 400;
    line-height: 24px;
    min-height: 46px;
}

.plan-price {
    display: flex;
    align-items: center;
    justify-content: start;
    color: #191D23;
    font-family: 'Lexend', sans-serif;
    font-size: 48px;
    font-style: normal;
    font-weight: 700;
    line-height: 60px;
    letter-spacing: 0.48px;
}

.card-active .card-text {
    color: #fff !important;
}

.card-active .plan-price {
    color: #fff !important;
}

.card-active .allow {
    color: #173EAD !important;
}

.card-active .allow .custom-border {
    background: #E8EDFB;
}

.card-active .allow span {
    color: #fff !important;
}


@media (max-width: 768px) {
    #subscriptionPlans {
        padding: 32px 0px;
    }
}
</style>


<script setup>
import { ref, onMounted } from 'vue';
import { useRouter } from 'vue-router';
import { useMasterStore } from '@/stores/master';

const plans = ref([]);
const router = useRouter();
const masterStore = useMasterStore();


onMounted(() => {
    axios.get(`/plan/list`, {
        headers: {
            "Content-Type": "application/json",
            Accept: "application/json",
        },
    })
        .then((res) => {
            plans.value = res.data.data;
        })
        .catch((error) => {
            console.error("Error fetching courses:", error);
        });
})

const setCurrency = (price) => {
    return masterStore.masterData.currency_symbol + price;
}

const checkout = (id) => {
    router.push({
        name: 'plan_checkout',
        query: {
            id: id
        }
    })
}
</script>
