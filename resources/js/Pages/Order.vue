<script setup>
import OrderComponent from '@/Components/OrderComponent.vue';
import { Head } from '@inertiajs/vue3';
import HeadingComponent from '@/Components/HeadingComponent.vue';
</script>

<template>
    <Head title="Orders" />
    
    <HeadingComponent projectName="Online Offline Restaurant"/>

    <OrderComponent />
    
</template>
