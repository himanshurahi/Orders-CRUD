<template>
    <Card :style="{ width: '40vw' }" class="m-auto mt-5">

        <template #title> Create Order </template>

        <template #content>
            <div class="form-container flex justify-content-center w-full">
                <Form :form-data="store.formData" :error-messages="store.errorMessages" :is-submitting="store.isSubmitting"
                    :order-statuses="store.orderStatuses" @submit="store.onSubmit" />
            </div>
        </template>
    </Card>
</template>


<script setup>
import { ref, watch, onMounted } from 'vue';

import Card from 'primevue/card';
import Form from "../../components/Form.vue"
import { useRoute, useRouter } from 'vue-router';
import { useOrdersStore } from '../../stores/orders';
const store = useOrdersStore();

//-----------------constants
const route = useRoute();

//-----------------mounted

onMounted(() => {
    if (route.params && route.params.id) {
        store.getOrder(route.params.id);
    }
});


//-----------------watchers
watch(() => store.formData.amount, (newValue, oldValue) => {
    const tax = parseFloat(newValue) * 0.1;
    store.formData.tax = tax.toFixed(2);
    store.formData.total_amount = (parseFloat(store.formData.amount) + tax).toFixed(2);
});


</script>