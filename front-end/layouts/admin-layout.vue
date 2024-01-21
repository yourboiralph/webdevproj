<!-- layouts/admin-layout.vue -->

<template>
    <div>
      <header>
        <div>
          <p>Hi There {{ state.user && state.user.first_name }}</p>
        </div>
        <div> 
          <nuxt-link to="" @click="logout">Log-out</nuxt-link>
        </div>
      </header>
  
      <div class="main-container">
        <div class="sidebar">
          <nav>
            <ul>
              <li><nuxt-link to="/admin/dashboard">Dashboard</nuxt-link></li>
              <li><nuxt-link to="/admin/admins">Admins</nuxt-link></li>
              <li><nuxt-link to="/admin/takers">Takers</nuxt-link></li>
              <li><nuxt-link to="/admin/diagnoses">Diagnoses</nuxt-link></li>
              <li><nuxt-link to="/admin/questions">Questions</nuxt-link></li>
              <li><nuxt-link to="/admin/options">Options</nuxt-link></li>
              <li><nuxt-link to="/admin/types">Depression Types</nuxt-link></li>
            </ul>
          </nav>
        </div>
        <div class="content-container">
          <slot />
        </div>
      </div>
    </div>
  </template>
  
  <script>
  export default {
    data() {
        return {
            isLoggedIn: false,
            state: {
              user: null,
            }
        };
    },
    mounted() {
        this.checkLogged();
        this.fetchUser();
    },
    methods: {
        checkLogged() {
            if (localStorage.getItem('_token')){
                this.isLoggedIn = true;
            }else{
                this.isLoggedIn = false;
            }
        },
        async fetchUser(){
    try{
        const response = await $fetch(`http://127.0.0.1:8000/api/admin`, {
        method: 'GET',
        headers:{
            'Authorization': 'Bearer ' + localStorage.getItem('_token')
        }
        })
        if(response.data){
            this.state.user = response.data
        }
        }
    catch(error){
        this.state.errors = error.response
        console.log('error', error)
    }
    },
        logout() {
            // Perform logout logic here
            // For example, remove _token from localStorage
            localStorage.removeItem('_token');
            this.isLoggedIn = false;
            navigateTo('/admin/login');
        }
    }

  
};
  </script>
  
  <style>
  /* Your custom layout styles go here */
  
  /* Container styles */
  body, html {
    height: 100%;
    margin: 0;
  }
  
  /* Header styles */
  header {
    background-color: #333;
    padding: 10px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    color: #fff;
  }
  
  header nuxt-link {
    text-decoration: none;
    color: #fff;
    margin: 0 10px;
  }
  
  /* Main container styles */
  .main-container {
    display: flex;
    height: 100vh;
  }
  
  /* Sidebar styles */
  .sidebar {
    background-color: #333;
    padding: 20px;
    color: #fff;
    width: 200px;
  }
  
  ul {
    list-style-type: none;
    padding: 0;
    margin: 0;
  }
  
  li {
    margin-bottom: 10px;
  }
  
  nuxt-link {
    text-decoration: none;
    color: #fff;
    font-size: 16px;
    padding: 10px;
    display: block;
    border-radius: 5px;
    transition: background-color 0.3s ease;
  }
  
  nuxt-link:hover {
    background-color: #555;
  }
  
  /* Content styles */
  .content-container {
    flex: 1;
    padding: 20px;
    margin-left: 20px;
  }
  </style>