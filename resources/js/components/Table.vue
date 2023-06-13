<script setup>
    import { onMounted, ref } from "vue";

    let tasks = ref([])

    onMounted(async () => {
        getTasks()
    })

    const getTasks = async () => {
        let response = await axios.get('/api/v1/tasks');
        tasks.value = response.data.data;
        console.log(tasks.value)
    }

    const deleteTask = async (id) => {
        await axios.delete(`/api/v1/tasks/${id}`)
        .then(response => {
            console.log(response.data)
            tasks.value = tasks.value.filter(task => task.id != id);
        })
        .catch(err => console.error(err))
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
                            @click="deleteTask(task.id)"
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
