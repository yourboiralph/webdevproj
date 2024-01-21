<template>
  <body class="bg-image">
      <section class="min-h-screen flex items-center justify-center fade-in">
  
          <div class="bg-orange-300 bg-opacity-20 flex rounded-2xl shadow-lg max-w-3xl p-5 items-center">
  
              <div class="sm:w-1/2 px-8">
                  <h2 class="font-bold text-2xl text-[#1F0805]">Login</h2>
                  <p class="text-sm mt-4 text-[#1F0805]">If you're already an Admin, easily log in</p>
  
                  <form class="flex flex-col gap-4" @submit.prevent="handleLogin()">
                      <input class="p-2 mt-8 rounded-2xl border" type="email" name="email" placeholder="Email" v-model="state.user.email">
                      <p>{{ state.errors && state.errors._data && state.errors._data.errors && state.errors._data.errors.email && state.errors._data.errors.email[0]}}</p>
                      <div class="relative ">
                          <input class="p-2 rounded-2xl border w-full" type="password" name="password" placeholder="Password" v-model="state.user.password">
                          <p>{{ state.errors && state.errors._data && state.errors._data.errors && state.errors._data.errors.password && state.errors._data.errors.password[0]}}</p>
  
                          <ion-icon class="absolute top-1/2 right-5 -translate-y-1/2 text-lg" name="eye-outline"></ion-icon>
                      </div>
                      <button class="bg-[#1F0805] rounded-full text-white py-2 hover:scale-105 duration-400" type="submit">Login</button>
                  </form>
  
                  <div class="mt-10 grid grid-cols-3 items-center text-gray-600">
                      <hr class="text-gray-600">
                      <p class="text-center">OR</p>
                      <hr class="text-gray-600">
                  </div>

  
                  <div class="mt-3 text-xs flex justify-between items-center">
                      <p> don't have an account?</p>
                      <button class="py-2 px-5 bg-white border rounded-xl hover:scale-105 duration-400"><a href="/admin/register">Register</a></button>
                  </div>
              </div>
  
              <div class="hidden md:block w-1/2">
                  <a href="admin/login"><img class="hover:scale-110 duration-150 rounded-2xl" src="../../img/mental_health.jpg" alt=""></a>
              </div>
          </div>
      </section>
  </body>
      <!-- <div class="registration-container">
        <h2>Login</h2>
        <form @submit.prevent="handleLogin()">
          <div class="form-group">
            <label for="email">Email:</label>
            <input type="email" id="email" v-model="state.user.email">
            <p>{{ state.errors && state.errors._data && state.errors._data.errors && state.errors._data.errors.email && state.errors._data.errors.email[0]}}</p>
  
          </div>
          <div class="form-group">
            <label for="password">Password:</label>
            <input type="password" id="password" v-model="state.user.password">
            <p>{{ state.errors && state.errors._data && state.errors._data.errors && state.errors._data.errors.password && state.errors._data.errors.password[0]}}</p>
          </div>
          <button type="submit">Login</button>
        </form>
      </div> -->
    </template>
  
  <script setup>
  const state = reactive({
    errors: [],
    user:{
      email: null,
      password: null
    }
  })

  
  
  async function handleLogin(){
    const params = {
      email: state.user.email,
      password: state.user.password
    }
    try{
      const response = await $fetch(`http://127.0.0.1:8000/api/login-admins`, {
      method: 'POST',
      body: params
    })
  

    if(response.data){
    localStorage.setItem('_token', response.data.token)

    console.log(response.data);
    if(response.data.user.is_verified === 0){
      try{
      const verify = await $fetch(`http://127.0.0.1:8000/api/send-verify-mail/admins/${params.email}`, {
      method: 'GET',
      headers:{
            'Authorization': 'Bearer ' + localStorage.getItem('_token')
          }
        });

        navigateTo('/admin/verify');
      }
      catch(error){

      }
    }
    else{
      navigateTo('/admin/dashboard');
    }
  }

  }
  catch(error){
    state.errors = error.response
    console.log(error.response)
    console.log('error', error)
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
          .bg-image {
              background-image: url('../../img/help.jpg');
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
