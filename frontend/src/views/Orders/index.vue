

<template>
    <main class="datatable-container">
        <DataTable v-model:selection="store.selectedOrders" lazy paginator :rows="10" :totalRecords="store.totalRecords"
            :rowsPerPageOptions="[5, 10, 20, 50]" :value="store.orders.data" :loading="store.loading" @page="store.onPage($event)"
            dataKey="id" tableStyle="min-width: 50rem" class="p-datatable">
            <template #header>
                <div class="flex align-items-center justify-content-between gap-2">
                    <div class="flex align-items-center gap-2">
                        <span class="text-xl text-900 font-bold">Orders</span>
                        <div v-if="store.selectedOrders.length">
                            <Button type="button" :label="`Selected ${store.selectedOrders.length}`"
                                @click="toggle($event, 'menu')" aria-haspopup="true" aria-controls="overlay_menu1"
                                size="small" />
                            <Menu ref="menu" id="overlay_menu1" :model="menuItems" :popup="true" />
                        </div>
                        <div>
                            <Button type="button" label="Actions" @click="toggle($event, 'commonMenu')" aria-haspopup="true"
                                aria-controls="overlay_menu" size="small" />
                            <Menu ref="commonMenu" id="overlay_menu" :model="commonMenuItems" :popup="true" />
                        </div>
                    </div>
                    <div class="flex gap-4">
                        <Button icon="pi pi-refresh" rounded raised />
                        <RouterLink to="/add">
                            <Button icon="pi pi-plus" rounded raised />
                        </RouterLink>
                    </div>
                </div>
            </template>
            <Column selectionMode="multiple" headerStyle="width: 3rem"></Column>
            <template class="text-center" #empty> No Orders found. </template>
            <Column field="name" header="Name">
                <template #body="slotProps">
                    {{ slotProps.data.name }}
                </template>
            </Column>
            <Column field="slug" header="Slug">
                <template #body="slotProps">
                    {{ slotProps.data.slug }}
                </template>
            </Column>
            <Column header="Is Active">
                <template #body="slotProps">
                    <Tag :value="store.getIsActiveStatus(slotProps.data.is_active).text"
                        :severity="store.getIsActiveStatus(slotProps.data.is_active).severity" />
                </template>
            </Column>
            <Column header="Status">
                <template #body="slotProps">
                    <Tag :value="store.getOrderStatus(slotProps.data.status).text"
                        :severity="store.getOrderStatus(slotProps.data.status).severity" />
                </template>
            </Column>
            <Column field="amount" header="Amount">
                <template #body="slotProps">
                    {{ slotProps.data.amount }}
                </template>
            </Column>
            <Column field="tax" header="Tax">
                <template #body="slotProps">
                    {{ slotProps.data.tax }}
                </template>
            </Column>
            <Column field="total_amount" header="Total Amount">
                <template #body="slotProps">
                    {{ slotProps.data.total_amount }}
                </template>
            </Column>
            <Column header="Actions">
                <template #body="slotProps">
                    <div class="flex justify-content-center gap-2">
                        <RouterLink :to="`/edit/${slotProps.data.id}`">
                            <i class="pi pi-pencil text-900 cursor-pointer"></i>
                        </RouterLink>
                    </div>
                </template>
            </Column>
            <template #footer> In total there are {{ store.orders ? store.orders.total : 0 }} orders. </template>
        </DataTable>
    </main>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import DataTable from 'primevue/datatable';
import Column from 'primevue/column';
import Tag from 'primevue/tag';
import Menu from 'primevue/menu';     
import { useOrdersStore } from '@/stores/orders'

// access the `store` variable anywhere in the component ✨
const store = useOrdersStore()

const menu = ref(null);
const commonMenu = ref(null);
const menuItems = ref([
    {
        label: 'Delete',
        icon: 'pi pi-fw pi-times',
        command: () => {
            store.itemAction(store.selectedOrders, 'delete');
        }
    },
    {
        label: 'Trash',
        icon: 'pi pi-fw pi-trash',
        command: () => {
            store.itemAction(store.selectedOrders, 'trash');
        }
    },
]);

const commonMenuItems = ref([
    {
        label: 'Delete All',
        icon: 'pi pi-fw pi-times',
        command: () => {
            store.itemAction(null, 'delete-all');
        }
    },
    {
        label: 'Trash All',
        icon: 'pi pi-fw pi-trash',
        command: () => {
            store.itemAction(null, 'trash-all');
        }
    },
    {
        label: 'Restore All',
        icon: 'pi pi-fw pi-undo',
        command: () => {
            store.itemAction(null, 'restore-all');
        }
    },
    {
        label: 'Active All',
        icon: 'pi pi-fw pi-check',
        command: () => {
           store.itemAction(null, 'active-all');
        }
    },
    {
        label: 'Inactive All',
        icon: 'pi pi-fw pi-times',
        command: () => {
            store.itemAction(null, 'inactive-all');
        }
    },
]);

//---------------onMounted
onMounted(() => {
    store.getList();
});

//--------------methods
function toggle(event, menuTarget) {
    if (menuTarget == 'menu') return menu.value.toggle(event);
    else return commonMenu.value.toggle(event);
};

//---

</script>

<style scoped>
.datatable-container {
    display: flex;
    justify-content: center;
    align-items: center;
}

.p-datatable {
    border: none;
    font-size: 1rem;

}

.p-datatable th,
.p-datatable td {
    text-align: center;
    padding: 1rem;
}

.p-datatable thead,
.p-datatable tfoot {
    background-color: #f5f5f5;
    color: #333;
}

.p-datatable tbody tr:hover {
    background-color: #f0f0f0;
}
</style>