<template>
  <body class="bg-image">
    <section class="min-h-screen flex items-center justify-center fade-in">
      <div class="bg-orange-300 bg-opacity-20 flex rounded-2xl shadow-lg max-w-3xl p-5 items-center">
        <div class="sm:w-1/2 px-8">
          <h2 class="font-bold text-2xl text-[#1F0805]">Register</h2>
          <p class="text-sm mt-4 text-[#1f0805]">If you're already a member, easily log in</p>
          <form class="flex flex-col gap-4" @submit.prevent="handleRegister">
            <div class="form-group">
              <h1 class="text-red-600"></h1>
              <input class="p-2 mt-8 rounded-2xl border w-full" type="text" v-model="state.user.first_name" placeholder="First name">
              <p>{{ getErrorMessage('first_name') }}</p>
            </div>
            <div class="form-group">
              <h1 class="text-red-600"></h1>
              <input class="p-2 rounded-2xl border w-full" type="text" v-model="state.user.last_name" placeholder="Last name">
              <p>{{ getErrorMessage('last_name') }}</p>
            </div>
            <div class="form-group">
              <h1 class="text-red-600"></h1>
              <input class="p-2 rounded-2xl border w-full" type="text" v-model="state.user.age" placeholder="Age">
              <p>{{ getErrorMessage('age') }}</p>
            </div>
            <div class="form-group">
              <h1 class="text-red-600"></h1>
              <input class="p-2 rounded-2xl border w-full" type="email" v-model="state.user.email" placeholder="Email">
              <p>{{ getErrorMessage('email') }}</p>
            </div>
            <div class="form-group">
              <h1 class="text-red-600"></h1>
              <div class="relative">
                <input class="p-2 rounded-2xl border w-full" type="password" v-model="state.user.password" placeholder="Password">
                <p>{{ getErrorMessage('password') }}</p>
                <ion-icon class="absolute top-1/2 right-5 -translate-y-1/2 text-lg" name="eye-outline"></ion-icon>
              </div>
            </div>
            <button class="bg-[#1F0805] rounded-full text-white py-2 hover:scale-105 duration-400" type="submit" name="register">Register</button>
            <div class="bg-[#1F0805] rounded-full text-white py-2 hover:scale-105 duration-400">
              <a class="flex justify-center items-center" href="login"><ion-icon name="arrow-back-outline"></ion-icon>Go back</a>
            </div>
          </form>
        </div>
        <div class="hidden md:block w-1/2">
          <a href="registration"><img class="hover:scale-110 duration-150 rounded-2xl" src="../img/areu_ok1.jpg" alt=""></a>
        </div>
      </div>
    </section>
  </body>
</template>

<script setup>
import { reactive, onMounted } from 'vue';

const state = reactive({
  errors: [],
  user: {
    first_name: null,
    last_name: null,
    age: null,
    email: null,
    password: null,
  },
});

const getErrorMessage = (field) => {
  const errorData = state.errors?._data?.errors;
  return errorData && errorData[field] ? errorData[field][0] : '';
};

async function handleRegister() {
  const params = {
    first_name: state.user.first_name,
    last_name: state.user.last_name,
    age: state.user.age,
    email: state.user.email,
    password: state.user.password,
  };

  try {
    const response = await $fetch(`http://127.0.0.1:8000/api/takers`, {
      method: 'POST',
      body: params,
    });

    if (response.data) {
      navigateTo('/login');
    }
  } catch (error) {
    state.errors = error.response;
    console.error(error.response);
    console.error('error', error);
  }
}

// Set a timeout to change the color
onMounted(() => {
  const validator = document.getElementById('validator');

  // Set a timeout to change the color
  setTimeout(() => {
    validator.classList.add('hidden');
  }, 1500);
});
</script>

<style scoped>
/* Add your CSS styles here */
.bg-image {
  background-image: url('../img/help1.jpg');
  background-size: cover;
  background-position: center;
  background-attachment: fixed;
  background-repeat: no-repeat;
}
.fade-in {
  opacity: 0;
  animation: fadeIn 2s ease forwards;
}

@keyframes fadeIn {
  from {
    opacity: 0;
  }
  to {
    opacity: 1;
  }
}
</style>
