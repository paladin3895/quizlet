<script setup>
import axios from 'axios';
import Layout from '@/Layouts/GuestLayout.vue';
import { Head, router } from '@inertiajs/vue3';
import { ref, defineProps } from 'vue';

const props = defineProps({
    session: Object,
    user: Object,
    results: Array,
});

const liveResults = ref(props.results);
const timer = ref(15);
const question = ref({});
const answer = ref('');
const isAnswered = ref(false);
const channel = window.Echo.channel(`App.Models.QuizSession.${props.session.id}`);
channel.listen('.App\\Events\\QuizQuestionCreated', (event) => {
    processNewQuestion(event.question);
})
channel.listen('.App\\Events\\QuizResultsUpdated', (event) => {
    liveResults.value = event;
})

async function submitAnswer(event) {
    event.preventDefault();
    isAnswered.value = true;
    return axios.post(`/quiz/${props.session.code}/answer`, {
        question_id: question.value.id,
        answer: answer.value,
    }).then(res => {
        alert('Your answer is correct');
    }).catch(err => {
        alert(err.response.data.message);
    })
}

async function requestNewQuestion() {
    return axios.post(`/quiz/${props.session.code}/question`, {
        current_question_id: question.value.id,
    }).then(res => {
        processNewQuestion(res.data);
    }).catch(err => {
        alert(err.response.data.message);
    })
}

function processNewQuestion(newQuestion) {
    if (question.value.id != newQuestion.id) {
        question.value = newQuestion;
        answer.value = '';
        isAnswered.value = false;
        startTimer();
    }
}

let countdown = null;
function startTimer() {
    timer.value = 15;
    clearInterval(countdown);
    countdown = setInterval(() => {
        if (timer.value > 0) {
            timer.value--;
        } else {
            clearInterval(countdown);
            requestNewQuestion();
            // Handle timer end (e.g., submit the quiz automatically)
        }
    }, 1000);
}

function getSortedResults(results) {
    return results.slice()
        .sort((val) => val.score);
}

function getAnswer(question) {
    return question.options[question.answer];
}

requestNewQuestion();
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
                            <div v-if="question.id" class="p-6 text-gray-900">
                                <!-- Timer -->
                                <div class="mb-4">
                                    <span class="text-lg font-semibold">{{ timer }} seconds to next question</span>
                                </div>
                                <!-- Main Question -->
                                <h2 v-if="isAnswered" class="text-xl font-semibold mb-4">Guess the country flag: {{ getAnswer(question) }}</h2>
                                <h2 v-else class="text-xl font-semibold mb-4">Guess the country flag:</h2>
                                <!-- Image -->
                                <img :src="'/quiz/flag/' + question.answer">
                                <!-- Multiple Choices -->
                                <form v-if="!isAnswered" @submit="submitAnswer">
                                    <ul class="space-y-2 mt-5">
                                        <li v-for="(label, value) in question.options">
                                            <label class="flex items-center">
                                                <input v-model="answer" type="radio" name="quiz" :value="value" :disabled="isAnswered" class="mr-2">
                                                <span>{{ label }}</span>
                                            </label>
                                        </li>
                                    </ul>
                                    <button type="submit"
                                        :disabled="isAnswered"
                                        class="mt-4 px-4 py-2 text-white bg-green-600 rounded-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                                        Submit
                                    </button>
                                </form>
                            </div>
                            <div v-else class="p-6 text-gray-900">
                                <h2 class="text-xl font-semibold mb-4">Please wait for the question...</h2>
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
                                    <li v-for="result in getSortedResults(liveResults)" class="flex justify-between">
                                        <span v-if="result.user_id == user.id" class="bg-green-500"><strong>{{ result.user_name }}</strong></span>
                                        <span v-else>{{ result.user_name }}</span>
                                        <span>{{ result.score }}</span>
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
<style scoped>
img {
    border: 3px solid;
}
</style>
