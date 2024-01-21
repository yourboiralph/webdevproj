<template>
  <div class="flex items-center justify-center h-screen bg-gray-100">
    <div class="bg-white p-8 rounded shadow-md text-center">
      <h1 class="text-3xl font-semibold mb-4 text-gray-800">Please Verify Your Email</h1>
      <p class="text-gray-600 mb-6">We've sent a verification link to your email address.</p>
      <button @click="home" class="bg-green-500 text-white px-6 py-3 rounded-full hover:bg-green-600 transition duration-300">Go back</button>
    </div>
  </div>
</template>

<script setup>

function home(){
  navigateTo('/admin/dashboard');
}
</script>
