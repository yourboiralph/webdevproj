<template>
  <div>
    <div class="actions">
      <button class="action-btn view mt-16" @click="back()">Back</button>
    </div>
    <div v-if="data">
      <div v-for="admin in data" :key="admin.id" class="admin-details">
        <p><strong>Admin no.:</strong> {{ admin.id }}</p>
        <p><strong>First Name:</strong> {{ admin.first_name }}</p>
        <p><strong>Last Name:</strong> {{ admin.last_name }}</p>
        <p><strong>Age:</strong> {{ admin.age }}</p>
        <p><strong>Email:</strong> {{ admin.email }}</p>
      </div>
    </div>
    <div v-else>
      <p>No admin details available.</p>
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

      const { adminID } = useRoute().params;
      const uri = 'http://127.0.0.1:8000/api/admins/' + adminID;

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
      this.$router.go(-1);
    },
  },
};
</script>

<style scoped>
.admin-details {
  margin-top: 20px;
}
</style>
