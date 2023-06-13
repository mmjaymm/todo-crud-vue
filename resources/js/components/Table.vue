<script setup>
    import { onMounted, ref, defineEmits } from "vue";

    let props = defineProps({
        tasks: Object
    })

    const emits = defineEmits(['handleDeleteTask']);

    const deleteSelectedTask = async (id) => {
        emits('handleDeleteTask', id);
    }

</script>

<template>
    <table class="table table-hover table-bordered text-center">
        <thead>
        <tr>
            <th>Subject</th>
            <th>Task</th>
            <th>Descriptions</th>
            <th>With Attachment</th>
            <th>Status</th>
            <th>Created At</th>
            <th>Actions</th>
        </tr>
        </thead>
        <tbody>
            <tr
                v-for="task in tasks"
                :key="task.id"
                v-if="tasks.length > 0"
            >
                <td>{{task.subject}}</td>
                <td>{{task.task}}</td>
                <td>{{task.descriptions}}</td>
                <td>{{task.attachment}}</td>
                <td>{{task.status}}</td>
                <td>{{task.created_at}}</td>
                <td>
                    <div class="btn-group">
                        <router-link
                            class="btn btn-info"
                            :to="{
                                name : 'update-task',
                                params: {id: task.id}
                                }"
                        >
                            <i class="bi bi-pencil-square"></i>
                        </router-link>
                        <button
                            type="button"
                            class="btn btn-danger"
                            @click="deleteSelectedTask(task.id)"
                        >
                            <i class="bi bi-trash"></i>
                        </button>
                    </div>
                </td>
            </tr>
            <tr v-else>
                <td colspan="7">No Record Found !</td>
            </tr>
        </tbody>
    </table>
</template>
