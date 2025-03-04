<script setup>
import {onMounted, ref} from "vue";

let name = ref([])
let email = ref([])
let password = ref([])
let errorMessage = ref([]);

const login = async () => {
    const formData = new FormData()
    formData.append('name', name.value)
    formData.append('email', email.value)
    formData.append('password', password.value)

    axios.post('api/v1/register', formData)
        .then((response) => {
            errorMessage.value = [];
            window.location.replace("http://localhost");
        }).catch((error) => {
        errorMessage.value = error.response.data.errors;
    })
};

</script>

<template>
    <div class="max-w-md mx-auto p-4">
        <h1 class="text-2xl mb-4">Register</h1>
        <form @submit.prevent="login">
            <input v-model="name" type="text" placeholder="Name" class="w-full mb-2 p-2 border">
            <input v-model="email" type="email" placeholder="Email" class="w-full mb-2 p-2 border">
            <input v-model="password" type="password" placeholder="Password" class="w-full mb-2 p-2 border">
            <button type="submit" class="bg-blue-500 text-white p-2">Create</button>
        </form>
    </div>
</template>

<style scoped>

</style>
