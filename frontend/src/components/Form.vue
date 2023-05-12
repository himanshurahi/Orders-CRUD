<template>
<form @submit="onSubmit" class="flex flex-column gap-2 w-full" >
               <div class="flex flex-column gap-1">
                    <label for="value" class="clickable-label">Name</label>   
                    <InputText required id="value" v-model="formData.name" type="text" :class="{ 'p-invalid': errorMessages.name }" aria-describedby="text-error" />
                    <small class="p-error" id="text-error" v-if="errorMessages.name">{{ errorMessages.name || '&nbsp;' }}</small>
               </div>

               <div class="flex flex-column gap-1">
                    <label for="value" class="clickable-label">Slug</label>   
                    <InputText required id="value" v-model="formData.slug" type="text" :class="{ 'p-invalid': errorMessages.name }" aria-describedby="text-error" />
                    <small class="p-error" id="text-error" v-if="errorMessages.slug">{{ errorMessages.slug || '&nbsp;' }}</small>
               </div>

               <div class="flex flex-column gap-1">
                    <label for="amount" class="clickable-label">Amount</label>
                    <InputText required id="amount" v-model="formData.amount" type="number" :class="{ 'p-invalid': errorMessage }" aria-describedby="text-error" />
                    <small class="p-error" id="text-error" v-if="errorMessages.amount">{{ errorMessages.amount || '&nbsp;' }}</small>
               </div>
                
               <div class="flex flex-column gap-1">
                    <label for="tax" class="clickable-label">Tax</label>
                    <InputText required id="tax" v-model="formData.tax" type="number" :class="{ 'p-invalid': errorMessage }" aria-describedby="text-error" />
                    <small class="p-error" id="text-error" v-if="errorMessages.tax">{{ errorMessages.tax || '&nbsp;' }}</small>
               </div>

               <div class="flex flex-column gap-1">
                    <label for="total_amount" class="clickable-label">Total Amount</label>
                    <InputText required id="total_amount" v-model="formData.total_amount" type="number" :class="{ 'p-invalid': errorMessage }" aria-describedby="text-error" />
                    <small class="p-error" id="text-error" v-if="errorMessages.total_amount">{{ errorMessages.total_amount || '&nbsp;' }}</small>
               </div>


                <div class="flex flex-column w-6rem gap-1">
                    <label for="value" class="clickable-label mb-2">Is Active</label>
                    <ToggleButton v-model="formData.is_active" onLabel="Active" offLabel="Inactive" />
                    <small class="p-error" id="text-error" v-if="errorMessages.is_active">{{ errorMessages.is_active || '&nbsp;' }}</small>
                </div>


                <div class="flex flex-column gap-1">
                    <label for="value" class="clickable-label mb-2">Status</label>
                    <Dropdown v-model="formData.status" :options="orderStatuses" optionLabel="label" optionValue="value" placeholder="Select Order Status" class="w-full md:w-14rem" />
                    <small class="p-error" id="text-error" v-if="errorMessages.status">{{ errorMessages.status || '&nbsp;' }}</small>
                </div>

                <Button type="submit" :label="`${formData.id ? 'Update' : 'Create'}`" :loading="isSubmitting" />

            </form>

</template>

<script setup>
import { defineProps, watch } from 'vue';

import InputText from 'primevue/inputtext';
import Checkbox from 'primevue/checkbox';
import ToggleButton from 'primevue/togglebutton';
import Dropdown from 'primevue/dropdown';


const props = defineProps({
    formData: {
        type: Object,
        required: false
    },
    errorMessages: {
        type: Object,
        required: false
    },
    isSubmitting: {
        type: Boolean,
        required: false
    },
    onSubmit: {
        type: Function,
        required: false
    },
    orderStatuses: {
        type: Array,
        required: true
    }
});

watch(() => props.formData.name, (newValue, oldValue) => {
    props.formData.slug = newValue.toLowerCase().replace(/ /g, '-');
});

</script>