<template>
    <body>

        <header>
    <h1>Psychology Dashboard</h1>
    <div>
        {{ state.user }}
    </div>
  </header>

  <nav>
    <div>
      <a href="#section1">Emotions</a>
      <a href="#section2">Mindfulness</a>
      <a href="#section3">Therapy</a>
    </div>
    <button class="logout-btn" @click="handleLogout()">Logout</button>
  </nav>

  <section id="section1">
    <div class="card">
      <h2>Emotions Analysis</h2>
      <p>Explore and analyze emotional patterns and trends.</p>
    </div>
  </section>

  <section id="section2">
    <div class="card">
      <h2>Mindfulness Techniques</h2>
      <p>Discover mindfulness exercises and techniques for mental well-being.</p>
    </div>
  </section>

  <section id="section3">
    <div class="card">
      <h2>Therapy Sessions</h2>
      <p>Track and manage therapy sessions and progress.</p>
    </div>
  </section>

  <footer>
    <p>&copy; 2024 Psych Insight</p>
  </footer>

    </body>
</template>

<script setup>

const state = reactive({
    user: null
})
    onMounted(()=>{
        navigateTo('/test');
    })
</script>

<style>
    body {
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      margin: 0;
      padding: 0;
      background-color: #f5f5f5;
    }

    section {
      padding: 2em;
    }

    .card {
      background-color: #fff;
      border-radius: 8px;
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
      padding: 1.5em;
      margin-bottom: 1em;
    }

    footer {
      background-color: #2c3e50;
      color: #ecf0f1;
      text-align: center;
      padding: 1em;
      position: fixed;
      bottom: 0;
      width: 100%;
    }

    .logout-btn {
      background-color: #e74c3c;
      color: #fff;
      padding: 0.5em 1em;
      border: none;
      border-radius: 4px;
      cursor: pointer;
    }

    .logout-btn:hover {
      background-color: #c0392b;
    }
</style>