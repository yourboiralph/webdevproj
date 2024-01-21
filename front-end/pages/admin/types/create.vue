<template>
  <div>
      <div class="actions">
      <button class="action-btn view mt-16" @click="back()">Back</button>
      </div> 
      <p v-if="successMessage" class="success">{{ successMessage }}</p>
      <p v-if="formErrors.general" class="error">{{ formErrors.general }}</p>

    <form @submit.prevent="submitForm" class="simple-form">
      <label for="type">Depression Type:</label>
      <input type="text" id="type" v-model="formData.type" class="form-field" required>
      <span class="error" v-if="formErrors.type">{{ formErrors.type }}</span>

      <label for="scoreRangeStart">Range Start at:</label>
      <input type="number" id="scoreRangeStart" v-model="formData.scoreRangeStart" class="form-field" required>
      <span class="error" v-if="formErrors.scoreRangeStart">{{ formErrors.scoreRangeStart }}</span>

      <label for="scoreRangeEnd">Range End at:</label>
      <input type="number" id="scoreRangeEnd" v-model="formData.scoreRangeEnd" class="form-field" required>
      <span class="error" v-if="formErrors.scoreRangeEnd">{{ formErrors.scoreRangeEnd }}</span>

      <label for="message">Message:</label>
      <textarea id="message" v-model="formData.message" class="form-field" required></textarea>
      <span class="error" v-if="formErrors.message">{{ formErrors.message }}</span>

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
    type: '',
    scoreRangeStart: '',
    scoreRangeEnd: '',
    message: '',
  };

  const formErrors = ref({});
  const successMessage = ref('');

  const submitForm = async () => {
    try {
      const response = await fetch('http://127.0.0.1:8000/api/depression-types/1', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
        },
        body: JSON.stringify(formData),
      });

      if (response.ok) {
          successMessage.value = 'Form submitted successfully!';
          formErrors.value = {};
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
      this.$router.push('/admin/types');
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
