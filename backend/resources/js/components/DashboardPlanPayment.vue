<template>
    <section class="my-2">
        <span class="d-block mb-2 mb-lg-3 fs-4 fw-bold">{{ $t('Home') }}/{{ $t('Plan Renewal History') }}</span>

        <div class="table-responsive mt-5">
            <table class="table">
                <thead>
                    <tr>
                        <th scope="col">{{ $t('SL No') }}</th>
                        <th scope="col">{{ $t('Plan Title') }}</th>
                        <th scope="col">{{ $t('Plan Duration') }}</th>
                        <th scope="col">{{ $t('Plan Start') }}</th>
                        <th scope="col">{{ $t('Plan End') }}</th>
                        <th scope="col">{{ $t('Pricing') }}</th>
                        <th scope="col">{{ $t('Action') }}</th>
                    </tr>
                </thead>
                <tbody class="border-start border-end" v-if="subscriptions && subscriptions.length > 0">
                    <tr v-for="(subscription, index) in subscriptions" :key="subscription.id">
                        <td class="fs-5">#{{ sprintFormat(index + 1) }}</td>
                        <td class="text-capitalize fs-5">{{ subscription?.plan?.title }}</td>
                        <td class="text-capitalize fs-5">
                            <span class="badge bg-success">
                                {{ subscription?.plan?.plan_type }}
                            </span>
                        </td>
                        <td :class="subscription?.status == true ? 'text-success fs-5' : 'text-danger fs-5'">
                            {{ subscription?.starts_at }}
                        </td>
                        <td :class="subscription?.status == true ? 'text-success fs-5' : 'text-danger fs-5'">
                            {{ subscription?.ends_at }}
                        </td>
                        <td class="text-capitalize fs-5">
                            {{ masterStore.masterData?.currency_symbol + subscription?.plan?.price }}
                        </td>
                        <td>
                            <RouterLink
                                :to="'/plan-checkout?id=' + subscription.plan.id + '&course_ids=' + subscription.course_ids.join(',')"
                                class="btn rounded btn-sm"
                                :class="selectedPlanId == subscription.plan.id ? 'pulse btn-danger' : 'btn-outline-primary'">
                                <i class="bi bi-bookmark-star me-2"></i>
                                {{ $t('Extend plan') }}
                            </RouterLink>
                        </td>
                    </tr>
                </tbody>
                <tbody class="border-start border-end" v-else>
                    <tr>
                        <td colspan="8" class="text-center text-danger">
                            {{ $t('there is no payment history found!') }}
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </section>
</template>

<style lang="scss" scoped>
.table {
    thead {
        tr {
            th {
                background-color: #677388;
                color: white;
                padding: 1rem;
                font-weight: normal;

                &:first-child {
                    border-top-left-radius: .7rem;
                }

                &:last-child {
                    border-top-right-radius: .7rem;
                }
            }
        }
    }

    tbody {
        tr {
            td {
                padding: 1rem;
            }
        }
    }
}

.pulse {
    animation: pulse 1s infinite;
}
</style>

<script setup>
import { useAuthStore } from '@/stores/auth'
import { useMasterStore } from '../stores/master';
import { computed, onMounted, ref } from 'vue';
import { useRoute } from 'vue-router';

const authStore = useAuthStore()
const masterStore = useMasterStore()
const route = useRoute()
const selectedPlanId = ref(Number(route.query.plan_id))

let subscriptions = ref([])

const sprintFormat = (number) => {
    return number.toString().padStart(3, '0');
}
computed(() => {
    sprintFormat()
})

const fetchEnrolledPlans = async () => {
    try {
        await axios.get(`/enroll-plans`, {
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'Authorization': 'Bearer ' + authStore.authToken
            }
        }).then((res) => {
            subscriptions.value = res.data.data;
        })
    } catch (error) {
        console.error('Error fetching plan wise enrollments:', error);
    }
};

onMounted(() => {
    fetchEnrolledPlans();
})

function shortTitle(title) {
    return title.length > 30 ? title.slice(0, 30) + '...' : title
}
</script>
