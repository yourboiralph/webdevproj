<template>
  <div>
    <div class="actions">
      <button class="action-btn view mt-16" @click="back()">Back</button>
    </div>
    <div v-if="data">
      <div v-for="typeDetail in data" :key="typeDetail.id" class="admin-details">
        <p><strong>Depression Type:</strong> {{ typeDetail.type }}</p>
        <p><strong>Score Range Start:</strong> {{ typeDetail.scoreRangeStart }}</p>
        <p><strong>Score Range End:</strong> {{ typeDetail.scoreRangeEnd }}</p>
        <p><strong>Message:</strong> {{ typeDetail.message }}</p>
      </div>
    </div>
    <div v-else>
      <p>No types details available.</p>
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
      const uri = 'http://127.0.0.1:8000/api/depression-types/' + id;

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
      this.$router.push('/admin/types');
    },
  },
};
</script>

<style scoped>
.admin-details {
  margin-top: 20px;
}
</style>