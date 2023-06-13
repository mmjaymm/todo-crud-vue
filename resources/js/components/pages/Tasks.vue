<script setup>

import { onMounted, reactive, ref } from 'vue'
import Table from "@/components/Table.vue";
import Modal from "@/components/Modal.vue";

const modal = ref({
    title: "Delete Task",
    body: "Are you sure you want to delete this task?",
    btnLabelProceed: "Yes",
    btnLabelCancel: "No",
    sShowModal: null
})

let tasks = ref([])
let selectedTaskId = ref(0);

onMounted(async () => {
    getTasks()

    modal.isShowModal = new bootstrap.Modal('#exampleModal', {
        keyboard: false
    })
})

const deleteSelectedTask = (taskID) => {
    selectedTaskId.value = taskID;
    modal.isShowModal.show()
}

const getTasks = async () => {
    let response = await axios.get('/api/v1/tasks');
    tasks.value = response.data.data;
    console.log(tasks.value)
}

const deleteTask = async () => {
    await axios.delete(`/api/v1/tasks/${selectedTaskId.value}`)
    .then(response => {
        console.log(response.data)
        tasks.value = tasks.value.filter(task => task.id != selectedTaskId.value)
        modal.isShowModal.hide()
    })
    .catch(err => console.error(err))
}


</script>

<template>
    <router-link class="btn btn-outline-success mb-3" :to="{name : 'add-task'}">
        <i class="bi bi-calendar-plus"></i> Add Task
    </router-link>

    <Modal
        :title="modal.title"
        :body="modal.body"
        :btnLabelProceed="modal.btnLabelProceed"
        :btnLabelCancel="modal.btnLabelCancel"
        @handle-btn-proceed="deleteTask"
    />
    <Table
        :tasks="tasks"
        @handle-delete-task="deleteSelectedTask"
    />
</template>
