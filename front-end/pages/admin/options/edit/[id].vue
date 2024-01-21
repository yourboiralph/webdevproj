<template>
    <div>
      <div class="actions">
        <button class="action-btn view mt-16" @click="back()">Back</button>
      </div>
      <p v-if="successMessage" class="success">{{ successMessage }}</p>
      <p v-if="formErrors.general" class="error">{{ formErrors.general }}</p>
  
      <form @submit.prevent="submitForm" class="simple-form">
  
        <label for="questionID">Question ID:</label>
        <input type="text" id="questionID" v-model="formData.questionID" class="form-field">
  
        <label for="option">Option:</label>
        <textarea id="option" v-model="formData.option" class="form-field"></textarea>
        <span class="error" v-if="formErrors.option">{{ formErrors.option }}</span>

  
        <label for="score">Score:</label>
        <input type="text" id="score" v-model="formData.score" class="form-field">
        <span class="error" v-if="formErrors.score">{{ formErrors.score }}</span>
  
        <input type="submit" value="Update Option" class="submit-button">
      </form>
    </div>
  </template>
  
  <script>
  import { ref, onMounted } from 'vue';
  import { useRoute, useRouter } from 'vue-router';
  
  export default {
    setup() {
      definePageMeta({
        layout: 'admin-layout',
      });
  
      const route = useRoute();
      const router = useRouter();
  
      const formData = ref({
        questionID: '',
        option: '',
        score: '',
      });
  
      const formErrors = ref({});
      const successMessage = ref('');
  
      // Fetch initial data or perform other setup tasks if needed
      onMounted(async () => {
        // Example: Fetch data for the option based on the route parameter
        const optionID = route.params.id;
        const uri = 'http://127.0.0.1:8000/api/options/' + optionID;
  
        const fetchData = async () => {
          try {
            const response = await fetch(uri);
  
            if (response.ok) {
              const data = await response.json();
              return data;
            } else {
              console.error('Error fetching data:', response.statusText);
              return null;
            }
          } catch (error) {
            console.error('Error fetching data:', error);
            return null;
          }
        };
  
        const data = await fetchData();
  
        // Initialize form fields with fetched values
        if (data) {
          formData.value = {
            questionID: data.option.questionID,
            option: data.option.option,
            score: data.option.score,
          };
        }
      });
  
      const submitForm = async () => {
        try {
          const optionID = route.params.id;
          const response = await fetch(`http://127.0.0.1:8000/api/options/${optionID}/1/edit`, {
            method: 'PUT',
            headers: {
              'Content-Type': 'application/json',
            }
          });
          console.log('After fetch:', response);
  
          if (response.ok) {
            formErrors.value = {};
            successMessage.value = 'Option updated successfully!';
          } else {
            const responseData = await response.json();
            if (responseData && responseData.errors) {
              formErrors.value = responseData.errors;
            } else {
              console.error('Unexpected error format:', responseData);
            }
          }
        } catch (error) {
          console.error('Error updating option:', error);
        }
      };
  
      const back = () => {
        router.push('/admin/options');
      };
  
      return { formData, formErrors, successMessage, submitForm, back };
    },
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
    margin-top: 10px;
  }
  </style>
  