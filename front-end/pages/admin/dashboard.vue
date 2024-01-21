<template>
  <div>
    <p>Welcome to the Dashboard!</p>
    <p></p>
  </div>
</template>

<script>
export default {
  setup() {
    definePageMeta({
      layout: 'admin-layout'
    });
  }
};
</script>

<style scoped>
/* Add your custom styles here if needed */
div {
  padding: 20px;
  border: 1px solid #ddd;
  border-radius: 5px;
  background-color: #fff;
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
  margin: 20px;
}

p {
  margin-bottom: 10px;
  font-size: 16px;
  color: #333;
}
</style>