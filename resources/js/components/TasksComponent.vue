<script setup>
import {onMounted, ref} from "vue";
import {TailwindPagination} from 'laravel-vue-pagination';

let list = ref([])
let sortField = ref('id')
let sortDirection = ref('desc')
let searchField = ref('')
let searchValue = ref('')
let listDirection = ref(['asc', 'desc'])
let listFields = ref(['id', 'title', 'description', 'status'])
let listStatuse = ref(['pending', 'in progress', 'done'])

let title = ref('')
let description = ref('')
let status = ref('')

let form = ref({
    title: '',
    description: '',
    status: '',
});

let errorMessage = ref([]);
let editTaskId = ref(false);

onMounted(async () => {
    getList()
})

const getList = async (page = 1) => {
    let response = await axios.get(`api/v1/tasks`, {
        params: {
            page,
            'sort_field': sortField.value,
            'sort_direction': sortDirection.value,
            'search_field': searchField.value,
            'search_value': searchValue.value
        },
        headers: {Authorization: `Bearer ${localStorage.getItem('token')}`}
    }).then((response) => {
        list.value = response.data
        errorMessage.value = [];
    }).catch((error) => {
        window.location.replace("/login");
        errorMessage.value = error.response.data.errors;
    })
};

const deleteTask = async (id) => {
    if (confirm("Are you sure to delete task " + id + "?")) {
        axios.delete('api/v1/tasks/' + id, {
            headers: {Authorization: `Bearer ${localStorage.getItem('token')}`},
        })
            .then((response) => {
                getList()
                errorMessage.value = [];
            }).catch((error) => {
            errorMessage.value = error.response.data.errors;
        })
    }
}

const updateTask = async () => {
    axios.put(('api/v1/tasks/' + editTaskId),
        {
            title: form.value.title,
            description: form.value.description,
            status: form.value.status
        },
        {
            headers: {
                'Content-Type': 'application/json',
                'Authorization': `Bearer ${localStorage.getItem('token')}`
            }
        }).then((response) => {
        errorMessage.value = [];
        getList();
        cancel()
    }).catch((error) => {
        errorMessage.value = error.response.data.errors;
    })
}

const createTask = async () => {

    const formData = new FormData()
    formData.append('title', form.value.title)
    formData.append('description', form.value.description)
    formData.append('status', form.value.status)

    axios.post('api/v1/tasks', formData, {
        headers: {Authorization: `Bearer ${localStorage.getItem('token')}`},
    })
        .then((response) => {
            errorMessage.value = [];
            getList()
        }).catch((error) => {
        errorMessage.value = error.response.data.errors;
    })
}

const cleanSorting = async () => {
    sortField.value = 'id'
    sortDirection.value = 'asc'
    getList()
};

const cleanSearching = async () => {
    searchField.value = ''
    searchValue.value = ''
    getList()
};

const editTask = async (data) => {
    if (confirm("Are you sure to edit task " + data.id + "?")) {
        editTaskId = data.id
        form.value.title = data.title
        form.value.description = data.description
        form.value.status = data.status
    }
};

const cancel = async () => {
    editTaskId = false
    form.value.title = ''
    form.value.description = ''
    form.value.status = ''
};

const logout = async () => {
    axios.post('api/v1/logout',[] ,{
        headers: {Authorization: `Bearer ${localStorage.getItem('token')}`},
    })
        .then((response) => {
            errorMessage.value = [];
            window.location.replace("/login");
        }).catch((error) => {
        errorMessage.value = error.response.data.errors;
    })
}

</script>

<template>
    <div class="m-5">
        <div v-for="error in errorMessage"
             class="p-4 mb-4 text-sm text-red-800 rounded-lg bg-red-50 dark:bg-gray-800 dark:text-red-400" role="alert">
            {{ error }}
        </div>

        <div class="cursor-pointer" @click="logout()">Logout</div>

        <h1 class="text-center mb-4 text-4xl font-extrabold leading-none tracking-tight text-gray-900 md:text-3xl lg:text-3xl mt-5">
            Tasks
        </h1>
        <div class="m-3">
            Sort by <select v-model="sortField" @change="getList" class="border border-gray-300">
            <option v-for="item in listFields">{{ item }}</option>
        </select>
            direction <select v-model="sortDirection" @change="getList" class="border border-gray-300">
            <option v-for="item in listDirection">{{ item }}</option>
        </select> <span @click="cleanSorting" class="cursor-pointer text-red-500">Clean</span>
        </div>

        <div class="m-3">
            Search by <select v-model="searchField" @change="getList" class="border border-gray-300">
            <option v-for="item in listFields">{{ item }}</option>
        </select>
            value <input class="border border-gray-300" v-model="searchValue" @change="getList"></input> <span
            class="cursor-pointer text-red-500" @click="cleanSearching">Clean</span>
        </div>

        <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
            <table class="w-full text-sm text-left rtl:text-right text-blue-100 dark:text-blue-100 mb-3">
                <thead class="text-xs text-white uppercase bg-blue-600 dark:text-white">
                <tr>
                    <th scope="col" class="px-6 py-3">
                        ID
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Title
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Description
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Status
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Action
                    </th>
                </tr>
                </thead>
                <tbody>
                <tr
                    class="bg-blue-500 border-b border-blue-400"
                    v-for="item in list.data"
                    :key="item.id"
                >
                    <th scope="row" class="px-6 py-4 font-medium text-blue-50 whitespace-nowrap dark:text-blue-100">
                        {{ item.id }}
                    </th>
                    <td class="px-6 py-4">
                        {{ item.title }}
                    </td>
                    <td class="px-6 py-4">
                        {{ item.description }}
                    </td>
                    <td class="px-6 py-4">
                        {{ item.status }}
                    </td>
                    <td class="px-6 py-4">
                        <span class="cursor-pointer" @click="editTask(item)">Edit</span> |
                        <span class="cursor-pointer" @click="deleteTask(item.id)">Delete</span>
                    </td>
                </tr>
                </tbody>
            </table>

            <TailwindPagination
                :data="list"
                @pagination-change-page="getList"
            />

        </div>

        <h2 v-if="editTaskId"
            class="text-center mb-4 text-4xl font-extrabold leading-none tracking-tight text-gray-900 md:text-2xl lg:text-2xl mt-5">
            Edit task #{{ editTaskId }}</h2>
        <h2 v-else
            class="text-center mb-4 text-4xl font-extrabold leading-none tracking-tight text-gray-900 md:text-2xl lg:text-2xl mt-5">
            Create new task</h2>
        <form>
            <div class="mb-6">
                <label for="default-input" class="block mb-2 text-sm font-medium text-gray-900">Title</label>
                <input
                    type="text"
                    id="default-input"
                    placeholder="Title"
                    v-model="form.title"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
            </div>


            <label for="message" class="block mb-2 text-sm font-medium text-gray-900">Description</label>
            <textarea
                id="message"
                rows="4"
                v-model="form.description"
                class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                placeholder="Description"></textarea>

            <label for="status" class="block mt-3 mb-2 text-sm font-medium text-gray-900 ">Status</label>
            <select
                id="status"
                v-model="form.status"
                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                <option v-for="item in listStatuse">{{ item }}</option>
            </select>

            <div v-if="editTaskId">
                <div
                    @click="updateTask"
                    class="mt-3 cursor-pointer text-white bg-green-700 hover:bg-green-800 focus:ring-4 focus:outline-none focus:ring-green-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800">
                    Submit
                </div>
                <div
                    @click="cancel"
                    class="mt-3 cursor-pointer text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                    Cancel
                </div>
            </div>

            <div v-else>
                <div
                    @click="createTask"
                    class="mt-3 cursor-pointer text-white bg-green-700 hover:bg-green-800 focus:ring-4 focus:outline-none focus:ring-green-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800">
                    Submit
                </div>
            </div>


        </form>

    </div>
</template>

<style scoped>

</style>
