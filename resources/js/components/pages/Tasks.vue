<script setup>
import Table from "@/components/Table.vue";
</script>

<template>
    <router-link class="btn btn-outline-success mb-3" :to="{name : 'add-task'}">
        <i class="bi bi-calendar-plus"></i> Add Task
    </router-link>

    <Table />
</template>
