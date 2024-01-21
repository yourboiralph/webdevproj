<template>
  <div>
    <div class="actions">
      <button class="action-btn view mt-16" @click="back()">Back</button>
    </div>
    <p v-if="successMessage" class="success">{{ successMessage }}</p>
    <p v-if="formErrors.general" class="error">{{ formErrors.general }}</p>

    <form @submit.prevent="submitForm" class="simple-form">
      <label for="type">Type:</label>
      <input type="text" id="type" v-model="formData.type" class="form-field">
      <span class="error" v-if="formErrors.type">{{ formErrors.type }}</span>

      <label for="scoreRangeStart">Range Start:</label>
      <input type="number" id="scoreRangeStart" v-model="formData.scoreRangeStart" class="form-field">
      <span class="error" v-if="formErrors.scoreRangeStart">{{ formErrors.scoreRangeStart }}</span>

      <label for="scoreRangeEnd">Range End:</label>
      <input type="number" id="scoreRangeEnd" v-model="formData.scoreRangeEnd" class="form-field">
      <span class="error" v-if="formErrors.scoreRangeEnd">{{ formErrors.scoreRangeEnd }}</span>

      <label for="message">Message:</label>
      <textarea id="message" v-model="formData.message" class="form-field" required></textarea>
      <span class="error" v-if="formErrors.message">{{ formErrors.message }}</span>

      <input type="submit" value="Update Type" class="submit-button">
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
      type: '',
      scoreRangeStart: '',
      scoreRangeEnd: '',
      message: '',
    });

    const formErrors = ref({});
    const successMessage = ref('');

    // Fetch initial data or perform other setup tasks if needed
    onMounted(async () => {
      // Example: Fetch data for the type based on the route parameter
      const typeID = route.params.id;
      const uri = 'http://127.0.0.1:8000/api/depression-types/' + typeID;

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
          type: data.depressionType.type,
          scoreRangeStart: data.depressionType.scoreRangeStart,
          scoreRangeEnd: data.depressionType.scoreRangeEnd,
          message: data.depressionType.message,
        };
      }
    });

    const submitForm = async () => {
      try {
        const typeID = route.params.id;
        const response = await fetch(`http://127.0.0.1:8000/api/depression-types/${typeID}/1` +`/edit`, {
          method: 'PUT',
          headers: {
            'Content-Type': 'application/json',
          },
          body: JSON.stringify(formData.value),
        });

        if (response.ok) {
          formErrors.value = {};
          successMessage.value = 'Type updated successfully!';
        } else {
          const responseData = await response.json();
          if (responseData && responseData.errors) {
            formErrors.value = responseData.errors;
          } else {
            console.error('Unexpected error format:', responseData);
          }
        }
      } catch (error) {
        console.error('Error updating type:', error);
      }
    };

    const back = () => {
      router.push('/admin/types');
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
