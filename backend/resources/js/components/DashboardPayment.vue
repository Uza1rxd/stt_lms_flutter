<template>
    <section class="my-2">
        <span class="d-block mb-2 mb-lg-3 fs-4 fw-bold">{{ $t('Home') }}/{{ $t('Payment History') }}</span>

        <div class="table-responsive mt-5">
            <table class="table">
                <thead>
                    <tr>
                        <th scope="col">{{ $t('Transaction ID') }}</th>
                        <th scope="col">{{ $t('Method') }}</th>
                        <th scope="col">{{ $t('Amount') }}</th>
                        <th scope="col">{{ $t('Status') }}</th>
                        <th scope="col">{{ $t('Pay Date') }}</th>
                    </tr>
                </thead>
                <tbody class="border-start border-end" v-if="transactions.length > 0">
                    <tr v-for="transaction in transactions" :key="transaction">
                        <td>#{{ sprintFormat(transaction.id) }}</td>
                        <td class="text-capitalize">
                            <span class="badge bg-success">{{ transaction.payment_method }}</span>
                        </td>
                        <td>{{ transaction.payment_amount }}</td>
                        <td class="text-success">
                            <span class="px-3 py-1 border border-success">{{ transaction.status }}</span>
                        </td>
                        <td>
                            {{ transaction.pay_at }}
                        </td>
                    </tr>
                </tbody>
                <tbody class="border-start border-end" v-else>
                    <tr>
                        <td colspan="6" class="text-center text-danger">
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
</style>

<script setup>
import { useAuthStore } from '@/stores/auth'
import { computed, ref } from 'vue';
const authStore = useAuthStore()

let transactions = ref({})

const sprintFormat = (number) => {
    return number.toString().padStart(6, '0');
}
computed(() => {
    sprintFormat()
})

axios.get(`/transactions`, {
    headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
        'Authorization': 'Bearer ' + authStore.authToken
    }
}).then((res) => {
    transactions.value = res.data.data.transactions
})

function shortTitle(title) {
    return title.length > 30 ? title.slice(0, 30) + '...' : title
}
</script>
