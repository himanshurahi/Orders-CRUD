import axios from 'axios';
import { defineStore } from 'pinia'
import { ref, computed } from 'vue'
import router from '../router';
export const useOrdersStore = defineStore('orders', {
  
    state: () => {
        return {
            orders: [],
            loading: false,
            totalRecords: 0,
            selectedOrders: [],
             orderStatuses : [
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
            ],
            
            formData : {
                id: '',
                name: '',
                amount: '',
                tax: '',
                total_amount: '',
                is_active: false,
                status: ''
            },
            
             errorMessages : {
                name: '',
                amount: '',
                tax: '',
                total_amount: '',
                is_active: false,
                status: ''
            },
            
             isSubmitting :false,
        }
    },

    actions: {
        getList(page = 1) {
            this.loading = true;
            const url = `/api/orders?page=${page}`;
            axios.get(url)
                .then(response => {
                    this.orders = response.data.data;
                    this.totalRecords = response.data.data.total;
                })
                .catch(error => {
                    console.log('error', error);
                }).finally(() => {
                    this.loading = false;
                });
        },

        onPage(event) {
            const page = event.page + 1;
            this.getList(page);
        },

         getIsActiveStatus(status) {
            if (status) return { severity: 'success', text: 'Active' }
            else return { severity: 'danger', text: 'Inactive' }
        },

        getOrderStatus(status) {
            status = status.toLowerCase();
            switch (status) {
                case 'pending':
                    return { severity: 'warning', text: 'Pending' }
                case 'delivered':
                    return { severity: 'success', text: 'Delivered' }
                case 'cancelled':
                    return { severity: 'danger', text: 'Cancelled' }
                default:
                    return { severity: 'info', text: status }
            }
        },
        //---
        
        async itemAction(items, type) {
            try {
                await axios.post('/api/orders/action', {
                    items: items,
                    type: type
                });
        
                this.getList();
                this.selectedOrders = [];
            } catch (error) {
                console.log(error);
            }
        
        },
        //---
         onSubmit(e) {
            e.preventDefault();
            this.isSubmitting = true;
            this.clearErrors();
            if (!this.formData.id) {
                this.createOrder();
                return;
            }
            this.updateOrder();
        },
        
        //---
        
         async handleResponse(response) {
            if (response.status == 'success') {
                this.clearForm();
                router.push({ name: 'orders' });
            }
        
            if (response.status == 'failed' && Object.keys(response.messages).length > 0) {
                Object.keys(response.messages).forEach(key => {
                    this.errorMessages[key] = response.messages[key][0];
                });
            }
        },
        
        //---
        getOrder(id) {
            axios.get('/api/orders/' + id)
                .then(response => {
                    this.formData = response.data.data;
                })
                .catch(error => {
                    console.log('error', error);
                });
        },
        
        //---
         createOrder() {
            axios.post('/api/orders', this.formData)
                .then(response => {
                    this.handleResponse(response.data);
                })
                .catch(error => {
                    console.log('error', error);
                }).finally(() => {
                    this.isSubmitting = false;
                });
        },
        
        //---
        
         updateOrder() {
            axios.put('/api/orders/' + this.formData.id, this.formData)
                .then(response => {
                    this.handleResponse(response.data);
                })
                .catch(error => {
                    console.log('error', error);
                }).finally(() => {
                    this.isSubmitting = false;
                });
        },
        
        //---
        
         clearErrors() {
            Object.keys(this.errorMessages).forEach(key => {
                this.errorMessages[key] = '';
            });
        },
        //---
         clearForm() {
            Object.keys(this.formData).forEach(key => {
                this.formData[key] = '';
            });
        }
    },
})