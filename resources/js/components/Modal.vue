<script setup>

    import { defineEmits } from 'vue';

    const props = defineProps({
        title: String,
        body: String,
        btnLabelProceed: String,
        btnLabelCancel: String,
        isShow: String
    })

    const emits = defineEmits(['handleBtnProceed'])

</script>

<template>
    <div
        class="modal fade"
        id="exampleModal"
        tabindex="-1"
        aria-labelledby="exampleModalLabel"
        aria-hidden="true"
    >
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">{{title}}</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                {{body}}
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{btnLabelCancel}}</button>
                <button
                    type="button"
                    class="btn btn-primary"
                    @click="emits('handleBtnProceed')"
                >
                    {{btnLabelProceed}}
                </button>
            </div>
            </div>
        </div>
    </div>
</template>
