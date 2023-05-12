<template>
    <Card :style="{ width: '40vw' }" class="m-auto mt-5">

        <template #title> Create Order </template>

        <template #content>
            <div class="form-container flex justify-content-center w-full">
                <Form :form-data="formData" :error-messages="errorMessages" :is-submitting="isSubmitting"
                    :order-statuses="orderStatuses" @submit="onSubmit" />
            </div>
        </template>
    </Card>
</template>


<script setup>
import { ref, watch, onMounted } from 'vue';

import Card from 'primevue/card';
import axios from 'axios';
import Form from "../../components/Form.vue"
import { useRoute, useRouter } from 'vue-router';

//-----------------constants
const router = useRouter();
const route = useRoute();

const orderStatuses = ref([
    {
        label: 'Pending',
        value: 'PENDING'
    },
    {
        label: 'Delivered',
        value: 'DELIVERED'
    },
    {
        label: 'Cancelled',
        value: 'CANCELLED'
    }
]);

const formData = ref({
    id: '',
    name: '',
    amount: '',
    tax: '',
    total_amount: '',
    is_active: false,
    status: ''
});

const errorMessages = ref({
    name: '',
    amount: '',
    tax: '',
    total_amount: '',
    is_active: false,
    status: ''
});

const isSubmitting = ref(false);

//-----------------mounted

onMounted(() => {
    if (route.params && route.params.id) {
        getOrder(route.params.id);
    }
});

//-------------functions

function onSubmit(e) {
    e.preventDefault();
    isSubmitting.value = true;
    clearErrors();
    if (!formData.value.id) {
        createOrder();
        return;
    }
    updateOrder();
}

//---

function handleResponse(response) {
    if (response.status == 'success') {
        clearForm();
        router.push({ name: 'orders' });
    }

    if (response.status == 'failed' && Object.keys(response.messages).length > 0) {
        Object.keys(response.messages).forEach(key => {
            console.log(key);
            errorMessages.value[key] = response.messages[key][0];
        });
    }
}

//---
function getOrder(id) {
    axios.get('/api/orders/' + id)
        .then(response => {
            formData.value = response.data.data;
        })
        .catch(error => {
            console.log('error', error);
        });
}

//---
function createOrder() {
    axios.post('/api/orders', formData.value)
        .then(response => {
            handleResponse(response.data);

        })
        .catch(error => {
            console.log('error', error);
            handleErrors(error);
        }).finally(() => {
            isSubmitting.value = false;
        });
}

//---

function updateOrder() {
    axios.put('/api/orders/' + formData.value.id, formData.value)
        .then(response => {
            handleResponse(response.data);
        })
        .catch(error => {
            console.log('error', error);
            handleErrors(error);
        }).finally(() => {
            isSubmitting.value = false;
        });
}

//---

function clearErrors() {
    Object.keys(errorMessages.value).forEach(key => {
        errorMessages.value[key] = '';
    });
}
//---
function clearForm() {
    Object.keys(formData.value).forEach(key => {
        formData.value[key] = '';
    });
}


//tax will be 10% of amount

watch(() => formData.value.amount, (newValue, oldValue) => {
    const tax = parseFloat(newValue) * 0.1;
    formData.value.tax = tax.toFixed(2);
    formData.value.total_amount = (parseFloat(formData.value.amount) + tax).toFixed(2);
});


</script>