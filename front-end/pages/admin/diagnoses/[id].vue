<template>
  <div>
    <div class="actions">
      <button class="action-btn view mt-16" @click="back()">Back</button>
    </div>
    <div v-if="data">
      <div v-for="diag in data" :key="diag.id" class="admin-details">
        <p><strong>Taker:</strong> {{ diag.taker }}</p>
        <p><strong>Taken at:</strong> {{ diag.taken_at }}</p>
        <p><strong>Total Score:</strong> {{ diag.total_score }}</p>
        <p><strong>Depression Type:</strong> {{ diag.depression_type.type }}</p>
        <p><strong>Depression Message:</strong> {{ diag.depression_type.message }}</p>
      </div>
    </div>
    <div v-else>
      <p>No diagnosis details available.</p>
    </div>
  </div>
</template>

<script>
export default {
  setup() {
    try {
      definePageMeta({
          layout: 'admin-layout',
        });

      const { id } = useRoute().params;
      const uri = 'http://127.0.0.1:8000/api/diagnoses/' + id;

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

      const data = ref(null);

      fetchData().then((result) => {
        data.value = result;
      });

      return { data };
    } catch (error) {
      console.error('Error fetching data:', error);
    }
  },

  methods: {
    back() {
      this.$router.push('/admin/diagnoses');
    },
  },
};
</script>
  
<style scoped>
.admin-details {
  margin-top: 20px;
}
</style>