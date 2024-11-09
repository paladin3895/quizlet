<script setup>
import Layout from '@/Layouts/GuestLayout.vue';
import { Head, router } from '@inertiajs/vue3';
import { ref } from 'vue';

</script>

<template>

    <Head title="Dashboard" />

    <Layout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                Dashboard
            </h2>
        </template>

        <div class="grid grid-cols-3 gap-4 w-full">
            <div class="col-span-2 m-6 overflow-hidden bg-white px-6 py-4 shadow-md sm:rounded-lg">
                <div class="py-4">
                    <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                        <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                            <div class="p-6 text-gray-900">
                                <!-- Timer -->
                                <div class="mb-4">
                                    <span class="text-lg font-semibold">{{ timer }} seconds to next question</span>
                                </div>
                                <!-- Main Question -->
                                <h2 class="text-xl font-semibold mb-4">Guess the country flag:</h2>
                                <!-- Image -->
                                <img src="path/to/image.jpg" alt="France" class="mb-4">
                                <!-- Multiple Choices -->
                                <ul class="space-y-2">
                                    <li>
                                        <label class="flex items-center">
                                            <input type="radio" name="quiz" value="A" class="mr-2">
                                            <span>Paris</span>
                                        </label>
                                    </li>
                                    <li>
                                        <label class="flex items-center">
                                            <input type="radio" name="quiz" value="B" class="mr-2">
                                            <span>London</span>
                                        </label>
                                    </li>
                                    <li>
                                        <label class="flex items-center">
                                            <input type="radio" name="quiz" value="C" class="mr-2">
                                            <span>Berlin</span>
                                        </label>
                                    </li>
                                    <li>
                                        <label class="flex items-center">
                                            <input type="radio" name="quiz" value="D" class="mr-2">
                                            <span>Madrid</span>
                                        </label>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-span-1 m-6 overflow-hidden bg-white px-6 py-4 shadow-md sm:rounded-lg">
                <div class="py-4">
                    <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                        <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                            <div class="p-6 text-gray-900">
                                <!-- User Scores -->
                                <h2 class="text-xl font-semibold mb-4">User Scores</h2>
                                <ul class="space-y-2">
                                    <li class="flex justify-between">
                                        <span>User 1</span>
                                        <span>85</span>
                                    </li>
                                    <li class="flex justify-between">
                                        <span>User 2</span>
                                        <span>90</span>
                                    </li>
                                    <li class="flex justify-between">
                                        <span>User 3</span>
                                        <span>75</span>
                                    </li>
                                    <li class="flex justify-between">
                                        <span>User 4</span>
                                        <span>95</span>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </Layout>
</template>
