<template>
  <div>
    <div class="actions">
      <button class="action-btn view mt-16" @click="back()">Back</button>
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

      <input type="submit" value="Update Taker" class="submit-button">
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
      first_name: '',
      last_name: '',
      age: '',
      email: '',
      password: '',
    });

    const formErrors = ref({});
    const successMessage = ref('');

    // Fetch initial data or perform other setup tasks if needed
    onMounted(async () => {
      // Example: Fetch data for the taker based on the route parameter
      const takerID = route.params.id;
      const uri = 'http://127.0.0.1:8000/api/takers/' + takerID;

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
          first_name: data.taker.first_name,
          last_name: data.taker.last_name,
          age: data.taker.age,
          email: data.taker.email,
          password: '',
        };
      }
    });

    const submitForm = async () => {
      try {
        const takerID = route.params.id;
        const response = await fetch(`http://127.0.0.1:8000/api/takers/${takerID}/edit`, {
          method: 'PUT',
          headers: {
            'Content-Type': 'application/json',
          },
          body: JSON.stringify(formData.value),
        });

        if (response.ok) {
          formErrors.value = {};
          successMessage.value = 'Taker updated successfully!';
        } else {
          const responseData = await response.json();
          if (responseData && responseData.errors) {
            formErrors.value = responseData.errors;
          } else {
            console.error('Unexpected error format:', responseData);
          }
        }
      } catch (error) {
        console.error('Error updating taker:', error);
      }
    };

    const back = () => {
      router.push('/admin/takers');
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
