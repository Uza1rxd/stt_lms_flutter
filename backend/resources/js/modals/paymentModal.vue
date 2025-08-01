<template>
    <div class="modal fade paymentModal" :id="'paymentModal' + subscription.id" aria-labelledby="paymentModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title fs-5 text-primary" id="paymentModalLabel">Select Payment Gateway</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="list-group">
                        <label v-for="gateway in masterStore?.masterData?.payment_methods" :key="gateway.id" class="list-group-item payment-option d-flex align-items-center mb-2">
                            <input type="radio" v-model="paymentGateway" :value="gateway.gateway" class="me-3">
                            <img :src="gateway.logo"
                                alt="PayPal" width="50" class="me-3">
                            {{gateway.name}}
                        </label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-success" @click="extendPlan(paymentGateway)">{{ $t('Proceed to payment') }}</button>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>
.paymentModal {
    background-color: #0000001c !important;
}
</style>

<script setup>
import { ref } from 'vue';
import { useMasterStore } from '../stores/master';

const masterStore = useMasterStore();
const paymentGateway = ref(null);

const props = defineProps({
    subscription: Object
})

const extendPlan = (gateway) => {
    console.log('Gateway selected:', gateway);
    console.log('Enrollment Id:', props.enrollment.id);
    console.log('Plan Id:', props.enrollment.subscriber?.plan?.id);
    console.log('Plan price:', props.enrollment.subscriber?.plan?.price);

};

</script>
