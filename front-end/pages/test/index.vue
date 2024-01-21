<template>
  <div class="flex justify-center items-center h-screen text-3xl bg-test fade-in relative" @keydown.left="prevQuestion" @keydown.right="nextQuestion" tabindex="0" @keydown.enter="submitAnswers">
    <div class="w-8/12 px-10 py-5 bg-black bg-opacity-50 rounded-2xl">
      <div class="carousel-container">
        <div v-for="(question, index) in questions" :key="question.id" :class="{ 'hidden': currentQuestionIndex !== index }" class="carousel-slide font-bold text-[#ffc599]">
          {{ question.question }}
          <ul>
            <p class="font-light text-xl text-white" v-for="option in question.options" :key="option.id">
              <label :class="{ 'text-yellow-500': selectedOptions[question.id] === option.id }">
                <input type="radio" :name="'question_' + question.id" :value="option.id" v-model="selectedOptions[question.id]">
                {{ option.option }}
              </label>
            </p>
          </ul>
        </div>
      </div>

      <div class="flex justify-between mt-4 md:text-sm">
        <button @click="prevQuestion" :disabled="currentQuestionIndex === 0" class="text-white p-2 hover:text-yellow-300 duration-500">Previous</button>
        <button @click="nextQuestion" :disabled="currentQuestionIndex === questions.length - 1" class="text-white px-2  md:p-2 hover:text-yellow-300 duration-500">Next</button>
        <button v-if="currentQuestionIndex === questions.length - 1" @click="submitAnswers" class="p-1 font-bold rounded text-green-300 hover:text-white hover:bg-green-400 duration-500">Submit Answers</button>
      </div>
    </div>

    <div v-if="errorMessage" class="error-message absolute top-24 left-1/2 transform -translate-x-1/2 mt-4 p-2 text-white bg-red-500 rounded text-base">
      {{ errorMessage }}
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';


const questions = ref([]);
const selectedOptions = ref({});
const currentQuestionIndex = ref(0);
const errorMessage = ref('');
const state = reactive({
  user: null,
  result: null
})

onMounted(()=>{
        navigateTo('/test');
    })

onMounted(fetchQuestions);
onMounted(()=>{
    checkLogged();
    fetchUser()
})


function checkLogged(){
  if(!localStorage.getItem('_token')){
    navigateTo('/login');
  }
}

async function fetchUser(){
try{
    const response = await $fetch(`http://127.0.0.1:8000/api/taker`, {
    method: 'GET',
    headers:{
        'Authorization': 'Bearer ' + localStorage.getItem('_token')
    }
    })
    if(response.data){
        state.user = response.data
    }
    }
catch(error){
    state.errors = error.response
    console.log('error', error)
}
}

async function fetchQuestions() {
  const response = await fetch('http://127.0.0.1:8000/api/questions-options');
  const data = await response.json();

  data.questions.forEach(question => (selectedOptions.value[question.id] = null));
  questions.value = shuffleArray(data.questions);
}

const nextQuestion = () => (currentQuestionIndex.value < questions.value.length - 1 && currentQuestionIndex.value++);
const prevQuestion = () => (currentQuestionIndex.value > 0 && currentQuestionIndex.value--);

const submitAnswers = async () => {
  try {
    const unansweredQuestions = Object.values(selectedOptions.value).filter(optionId => optionId === null);

    if (unansweredQuestions.length > 0) {
      console.error('Please provide answers for all questions before submitting.');
      errorMessage.value = 'Please provide answers for all questions before submitting.';

      setTimeout(() => {
        errorMessage.value = '';
      }, 8000);
      return;
    }

    const response = await fetch(`http://127.0.0.1:8000/api/diagnoses/${state.user.id}`, {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify(selectedOptions.value),
    });

    const data = await response.json();
    console.log(data);

    errorMessage.value = '';

    navigateTo('/test/result');
    
  } catch (error) {
    console.error('Error submitting answers:', error);
    errorMessage.value = 'An error occurred while submitting answers.';
  }
};

const shuffleArray = array => {
  for (let i = array.length - 1; i > 0; i--) {
    const j = Math.floor(Math.random() * (i + 1));
    [array[i], array[j]] = [array[j], array[i]];
  }
  return array;
};
</script>

<style scoped>

</style>