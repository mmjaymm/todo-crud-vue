<script setup>
    import { onMounted, ref } from "vue";
    import {useRoute, useRouter} from "vue-router";

    const router = useRouter();
    const route = useRoute();

    onMounted(() => {
        if (route.params.id !== undefined) {
            formLabel.value = "Update Task";
            btnSubmitLabel.value = "Update";
            getTask();
        }
    })

    const formLabel = ref("Create Task");
    const btnSubmitLabel = ref("Submit");

    const task = ref({
        id: 0,
        subject: '',
        task: '',
        descriptions: '',
        attachment: null,
        status: ''
    })

    const insertTask = async (e) => {
        const formData = new FormData(e.target);
        // const file = fileInput.value.files[0];
        // formData.append('file', file);

        if (route.params.id) {
            //Update
            formData.append('_method', "PUT");
            await axios.post(`/api/v1/tasks/${route.params.id}/update`, formData)
            .then(response => {
                console.log(response.data);
                router.push({ name: 'tasks-list' })
            })
            .catch(err => console.error(err))
        } else {
            //Add
            await axios.post(`/api/v1/tasks`, formData)
            .then(response => {
                console.log(response.data);
                router.push({ name: 'tasks-list' })
            })
            .catch(err => console.error(err))
        }

    }

    const getTask = async () => {
        await axios.get(`/api/v1/tasks/${route.params.id}`)
        .then(response => {
            task.value = response.data.data;
        })
        .catch(err => console.error(err))
    }

</script>

<template>
    <h5>{{formLabel}}</h5>

    <form @submit.prevent="insertTask" ref="taskForm">
        <div class="mb-3">
            <label for="subject" class="form-label">Subject</label>
            <input type="text" class="form-control" v-model="task.subject" name="subject" id="subject" placeholder="Enter Subject...">
        </div>
         <div class="mb-3">
            <label for="task" class="form-label">Task Name</label>
            <input type="text" class="form-control" v-model="task.task" id="task" name="task" placeholder="Enter Task Name...">
        </div>
         <div class="mb-3">
            <label for="descriptions" class="form-label">Descriptions</label>
            <textarea class="form-control" name="descriptions" v-model="task.descriptions" id="descriptions" rows="3" placeholder="Enter Task Descriptions..."></textarea>
        </div>
        <div class="mb-3">
            <label for="attachment" class="form-label">Attachment</label>
            <input class="form-control" type="file" name="attachment" id="attachment" ref="fileInput">
        </div>
        <div class="mb-3">
            <label for="status" class="form-label">Task Status</label>
            <select class="form-select" id="status" name="status" v-model="task.status">
                <option selected>Please Select Status...</option>
                <option value="new">New</option>
                <option value="inprogress">Inprogress</option>
                <option value="completed">Completed</option>
            </select>
        </div>

        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
            <router-link class="btn btn-outline-danger" to="/tasks">
                <i class="bi bi-arrow-90deg-left"></i> Back
            </router-link>
        <button type="submit" class="btn btn-success">{{btnSubmitLabel}}</button>
        </div>

    </form>
</template>
