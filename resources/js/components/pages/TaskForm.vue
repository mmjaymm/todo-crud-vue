<script setup>
    import { onMounted, ref, reactive } from "vue";
    import { useRoute, useRouter} from "vue-router";

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
        status: '',
    })

    const formField = reactive({
      validated: false,
      hasAttachment: false,
      errors: {
        subject: '',
        descriptions: '',
        task: '',
        attachment: '',
        status: ''
      }
    });

    const submitTask = async (e) => {
        const formData = new FormData(e.target);
        validateForm()

        if (!formField.validated || !formField.hasAttachment) {
            return;
        }
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

    const validateForm = () => {

        formField.validated = (!task.subject || !task.task || !task.descriptions || !task.status || formField.hasAttachment) ? true : false;

        if(!task.subject) {
            formField.errors.subject = "Subject is required!"
        }

        if(!task.task) {
            formField.errors.task = "Task Name is required!"
        }

        if(!task.descriptions) {
            formField.errors.descriptions = "Task Descriptions is required!"
        }

        if(!task.status) {
            formField.errors.status = "Please choose one!"
        }

        if(!formField.hasAttachment) {
            formField.errors.attachment = "Please choose an attachment!"
        }
    }

    const chooseFile = (e) => {
        var files = e.target.files;
        if (files.length) {
            formField.hasAttachment = true
            formField.validated = true
        } else {
            formField.hasAttachment = false
            formField.validated = false
        }
    }
</script>

<template>
    <h5>{{formLabel}}</h5>

    <form
        @submit.prevent="submitTask"
        class="needs-validation"
        :class="{ 'was-validated': formField.validated }"
        novalidate
    >
        <div class="mb-3">
            <label for="subject" class="form-label">Subject</label>
            <input
                type="text"
                class="form-control"
                :class="{ 'is-invalid': formField.errors.subject }"
                v-model="task.subject"
                name="subject"
                id="subject"
                placeholder="Enter Subject..."
                required
            >
            <div
                v-if="!task.subject"
                class="invalid-feedback"
            >{{ formField.errors.subject }}</div>
        </div>
         <div class="mb-3">
            <label for="task" class="form-label">Task Name</label>
            <input
                type="text"
                class="form-control"
                :class="{ 'is-invalid': formField.errors.task }"
                v-model="task.task" id="task" name="task" placeholder="Enter Task Name..." required>
            <div
                v-if="!task.task"
                class="invalid-feedback"
            >{{ formField.errors.task }}</div >
        </div>
         <div class="mb-3">
            <label for="descriptions" class="form-label">Descriptions</label>
            <textarea
                class="form-control"
                :class="{ 'is-invalid': formField.errors.descriptions }"
                name="descriptions"
                v-model="task.descriptions"
                id="descriptions"
                rows="3"
                placeholder="Enter Task Descriptions..."
                required
            ></textarea>
            <div
                v-if="!task.descriptions"
                class="invalid-feedback"
            >{{ formField.errors.descriptions }}</div >
        </div>
        <div class="mb-3">
            <label for="attachment" class="form-label">Attachment</label>
            <input
                class="form-control"
                type="file"
                name="attachment"
                id="attachment"
                :class="{ 'is-invalid': formField.errors.attachment }"
                @change="chooseFile"
                required>
            <div
                v-if="!formField.hasAttachment"
                class="invalid-feedback"
            >{{ formField.errors.attachment }}</div >
        </div>
        <div class="mb-3">
            <label for="status" class="form-label">Task Status</label>
            <select class="form-select" id="status" name="status" v-model="task.status" :class="{ 'is-invalid': formField.errors.status }" required>
                <option selected>Please Select Status...</option>
                <option value="new">New</option>
                <option value="inprogress">Inprogress</option>
                <option value="completed">Completed</option>
            </select>
            <div
                v-if="!task.status"
                class="invalid-feedback"
            >{{ formField.errors.status }}</div >
        </div>

        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
            <router-link class="btn btn-outline-danger" to="/tasks">
                <i class="bi bi-arrow-90deg-left"></i> Back
            </router-link>
        <button type="submit" class="btn btn-success">{{btnSubmitLabel}}</button>
        </div>

    </form>
</template>
