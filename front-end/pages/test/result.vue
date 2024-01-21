<template>
  <div v-if="state.result" class="bg-gradient-to-b from-white to-ffc599 bg-result">
    <div class="flex justify-center items-center fade-in">
      <div>
        <h2 class="text-3xl font-bold mb-4 mt-44 text-center text-[#ffc599]">Recent Diagnosis</h2>
        <div class="bg-black bg-opacity-30 p-8 rounded-lg shadow-md">
          <div class="text-white text-2xl">
            <div class="grid grid-cols-2">
              <p class="mb-2 text-xl">Taker:</p>
              <span class="text-2xl font-bold">{{ state.result.taker }}</span>
              <p class="mb-2 text-xl">Depression Type:</p>
              {{ state.result.depression_type?.type }}
              <p class="mb-2 text-xl">Total Score:</p>
              {{ state.result.total_score }}
            </div>
            <p class="mb-2 text-xs">Taken At: <span class="italic">{{ state.result.taken_at }}</span></p>
          </div>
        </div>

        <div class="bg-black bg-opacity-30 p-8 rounded-lg shadow-md mt-10 mb-24">
          <h3 class="text-3xl font-semibold mb-2 text-[#ffc599]">Responses:</h3>
          <ul class="text-white">
            <li v-for="(response, index) in state.result.responses" :key="index" class="mb-4">
              <p class="border mt-10"></p>
              <p class="mb-2 text-yellow-200"><span class="font-semibold">Question:</span> {{ response.question }}</p>
              <p class="mb-2"><span class="font-semibold text-green-200">Answer:</span> {{ response.answer }}</p>
              <p class="mb-2"><span class="font-semibold">Score:</span> {{ response.score }}</p>
            </li>
          </ul>
        </div>
      </div>
    </div>
    <div class="flex justify-center items-center pb-44">
      <div class="bg-black bg-opacity-30 text-white w-96 p-3 rounded-xl">
        <p class="text-center">Would you like a copy of your result?</p>
        <div class="flex justify-center items-center">
          <button class="p-1 mt-2 bg-yellow-900 rounded-lg hover:scale-110 button duration-500 hover:bg-transparent" @click="sendResultEmail(state.result)">Send Copy</button> 
        </div>
      </div>
    </div>
    <div v-if="state.successMessage" class="success-message text-4xl font-bold p-5">
      {{ state.successMessage }}
    </div>
  </div>
</template>

<script setup>
const state = reactive({
user: null,
result: null,
successMessage: '',
});

onMounted(()=>{
    checkLogged();
    fetchUser();
    getResult();
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


async function getResult() {
try {
    const response = await $fetch(`http://127.0.0.1:8000/api/recent-diagnosis`, {
        method: 'GET',
        headers: {
            'Authorization': 'Bearer ' + localStorage.getItem('_token')
        }
    });

    if (response && response.diagnosis) {
        console.log(response.diagnosis);
        state.result = response.diagnosis;
    }
} catch (error) {
    state.errors = error.response;
    console.error('Error fetching recent diagnosis:', error);
}
}

async function sendResultEmail(result) {
try {
  const response = await $fetch('http://127.0.0.1:8000/api/send-result-email', {
    method: 'POST',
    headers: {
      'Authorization': 'Bearer ' + localStorage.getItem('_token'),
      'Content-Type': 'application/json',
    },
    body: JSON.stringify({ result }),
  });

  console.log(response);

  if (response.success) {
    state.successMessage = 'Copy sent successfully!';

    setTimeout(() => {
      state.successMessage = '';
    }, 5000);
  } else {
    state.successMessage = 'Failed to send copy. Please try again.';
  }
} catch (error) {
  console.error('Error in send-result-email endpoint:', error);
  state.successMessage = 'An error occurred. Please try again later.';
}
}
</script>

<style scoped>

</style>