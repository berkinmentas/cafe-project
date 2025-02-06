<template>
    <div>
        <Dropzone
            class="dropzone-custom"
            :options="dropzoneOptions"
            @vdropzone-success="handleSuccess"
            @vdropzone-error="handleError"
        />
    </div>
</template>

<script setup>
import { ref } from 'vue';
// import 'vue3-dropzone/dist/vue3-dropzone.css'
import Dropzone from 'vue3-dropzone'

const dropzoneOptions = {
    url: '/api/admin/products',
    thumbnailWidth: 150,
    maxFilesize: 2,
    headers: {
        "Authorization": `Bearer ${localStorage.getItem('access_token')}`,
        "Accept": "application/json"
    },
    acceptedFiles: ".jpeg,.jpg,.png",
    addRemoveLinks: true,
    dictDefaultMessage: "Resim yüklemek için tıklayın veya sürükleyin",
};

const handleSuccess = (file, response) => {
    console.log('Upload success:', response);
};

const handleError = (file, message) => {
    console.error('Upload error:', message);
};
</script>

<style>
/* Dropzone stillerini buraya ekleyelim */
.dropzone-custom {
    min-height: 150px;
    border: 2px dashed #3490dc;
    border-radius: 5px;
    background: white;
    padding: 20px;
}

.dropzone-custom:hover {
    border-color: #2779bd;
}

.dropzone-custom .dz-message {
    text-align: center;
    margin: 2em 0;
}

.dropzone-custom .dz-preview {
    margin: 10px;
}

.dropzone-custom .dz-image {
    border-radius: 4px;
}

.dropzone-custom .dz-error-message {
    color: #dc2626;
}
</style>
