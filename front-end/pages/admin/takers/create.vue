<template>
    <div>
        <div class="actions">
        <button class="action-btn view" @click="back()">Back</button>
        </div> 
        <p v-if="successMessage" class="success">{{ successMessage }}</p>
        <p v-if="formErrors.general" class="error">{{ formErrors.general }}</p>

      <form @submit.prevent="submitForm" class="simple-form">
        <label for="first_name">First Name:</label>
        <input type="text" id="first_name" v-model="formData.first_name" class="form-field">
        <span class="error" v-if="formErrors.first_name">{{ formErrors.first_name }}</span>
  
        <label for="last_name">Last Name:</label>
        <input type="text" id="last_name" v-model="formData.last_name" class="form-field">
        <span class="error" v-if="formErrors.last_name">{{ formErrors.last_name }}</span>
  
        <label for="age">Age:</label>
        <input type="text" id="age" v-model="formData.age" class="form-field">
        <span class="error" v-if="formErrors.age">{{ formErrors.age }}</span>
  
        <label for="email">Email:</label>
        <input type="text" id="email" v-model="formData.email" class="form-field">
        <span class="error" v-if="formErrors.email">{{ formErrors.email }}</span>
  
        <label for="password">Password:</label>
        <input type="password" id="password" v-model="formData.password" class="form-field">
        <span class="error" v-if="formErrors.password">{{ formErrors.password }}</span>
  
        <input type="submit" value="Submit" class="submit-button">
      </form>
    </div>
  </template>

  <script>
import { ref } from 'vue';

export default {
  setup() {
    definePageMeta({
        layout: 'admin-layout',
      });

    const formData = {
      first_name: '',
      last_name: '',
      age: '',
      email: '',
      password: '',
    };

    const formErrors = ref({});
    const successMessage = ref('');

    const submitForm = async () => {
      try {
        const response = await fetch('http://127.0.0.1:8000/api/takers', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
          },
          body: JSON.stringify(formData),
        });

        if (response.ok) {
            formErrors.value = {};
            successMessage.value = 'Form submitted successfully!';
        } else {
          const responseData = await response.json();
          if (responseData && responseData.errors) {
            formErrors.value = responseData.errors;
          } else {
            console.error('Unexpected error format:', responseData);
          }
        }
      } catch (error) {
        console.error('Error submitting form:', error);
      }
    };

    const back = () => {
      this.$router.go(-1);
    };

    return { formData, formErrors, successMessage, submitForm, back };
  },

  methods: {
    back() {
        this.$router.push('/admin/takers');
    },
  }
};
</script>
  
<style scoped>
  .simple-form {
    max-width: 250px;
    margin: auto;
  }

  .form-field {
    width: calc(100% - 12px);
    padding: 8px;
    margin-bottom: 10px;
    box-sizing: border-box;
    border: 1px solid black;
    border-radius: 4px;
  }

  label {
    display: block;
    margin-bottom: 6px;
  }

  .error,
  .success {
    text-align: center;
    margin-top: 10px;
  }

  .error {
    color: red;
    font-size: 12px;
    display: block;
  }

  .success {
    color: green;
    font-size: 12px;
    display: block;
  }

  .submit-button {
    background-color: #4caf50;
    color: white;
    padding: 8px;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    margin-top: 10px; /* Add some spacing between the form and the button */
  }
</style>
