<script setup>
import axios from 'axios';
import Layout from '@/Layouts/GuestLayout.vue';
import { Head, router } from '@inertiajs/vue3';
import { ref } from 'vue';

const sessionCode = ref('');
const sessionName = ref('');

async function joinQuizSession(event) {
    event.preventDefault();
    return axios.get('/quiz', {
        params: {
            code: sessionCode.value,
        }
    }).then(res => {
        router.visit(`/quiz/${res.data.code}`);
    }).catch(err => {
        alert(err.response.data.message);
    })
};

async function createQuizSession(event) {
    event.preventDefault();
    return axios.post('/quiz', {
        name: sessionName.value,
    }).then(res => {
        router.visit(`/quiz/${res.data.code}`);
    }).catch(err => {
        alert(err.response.data.message);
    })
};
</script>

<template>

    <Head title="Dashboard" />

    <Layout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                Dashboard
            </h2>
        </template>

        <div class="mt-6 w-full overflow-hidden bg-white px-6 py-4 shadow-md sm:max-w-md sm:rounded-lg">
            <div class="py-4">
                <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                    <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                        <div class="p-6 text-gray-900">
                            <h3 class="text-lg font-medium leading-6 text-gray-900 mb-4">Enter Quiz session code to
                                join:</h3>
                            <form @submit="joinQuizSession" class="flex items-center space-x-4">
                                <input v-model="sessionCode" type="text" placeholder="Code"
                                    class="px-4 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500" />
                                <button type="submit"
                                    class="px-4 py-2 text-white bg-indigo-600 rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                                    Join
                                </button>
                            </form>
                        </div>
                        <div class="p-6 text-gray-900">
                            <h3 class="text-lg font-medium leading-6 text-gray-900 mb-4">Or create new session:</h3>
                            <form @submit="createQuizSession" class="flex items-center space-x-4">
                                <input v-model="sessionName" type="text" placeholder="Enter session name"
                                    class="px-4 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500" />
                                <button type="submit"
                                    class="px-4 py-2 text-white bg-green-600 rounded-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                                    Create
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </Layout>
</template>
