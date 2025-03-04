<script setup>
import {onMounted, ref} from "vue";

let email = ref([])
let password = ref([])
let errorMessage = ref([]);

const login = async () => {
    const formData = new FormData()
    formData.append('email', email.value)
    formData.append('password', password.value)

    axios.post('api/v1/login', formData)
        .then((response) => {
            errorMessage.value = [];
            localStorage.setItem('token', response.data.token);
            window.location.replace("http://localhost");
        }).catch((error) => {
        errorMessage.value = error.response.data.errors;
    })
};

const registration = async () => {
    window.location.replace("/register");
};


</script>

<template>
    <div>
        <div v-for="error in errorMessage"
             class="p-4 mb-4 text-sm text-red-800 rounded-lg bg-red-50 dark:bg-gray-800 dark:text-red-400" role="alert">
            {{ error }}
        </div>
        <div class="cursor-default text-right" @click="registration">Registration</div>

        <div class="max-w-md mx-auto p-4">
            <h1 class="text-2xl mb-4">Login</h1>
            <form @submit.prevent="login">
                <input v-model="email" type="email" placeholder="Email" class="w-full mb-2 p-2 border">
                <input v-model="password" type="password" placeholder="Password" class="w-full mb-2 p-2 border">
                <button type="submit" class="bg-blue-500 text-white p-2">Login</button>
            </form>
        </div>
    </div>
</template>

<style scoped>

</style>
