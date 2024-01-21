
<template>
  <div>
    <p>Diagnoses List</p>
    <table>
      <thead>
        <tr>
          <th>Taker</th>
          <th>Score</th>
          <th>Depression</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="t in data.Diagnosis" :key="t.id">
          <td>{{ t.taker }}</td>
          <td>{{ t.total_score }}</td>
          <td>{{ t.depression_type.type }}</td>
          <td>
            <button class="action-btn view" @click="view(t.id)">View</button>
          </td>
        </tr>
      </tbody>
    </table>
  </div>
</template>


<script>
export default { 
  async setup() {
    try {
      const { data } = await useFetch('http://127.0.0.1:8000/api/diagnoses/');
    
      definePageMeta({
        layout: 'admin-layout',
      }); 
    
      return { data };
    } catch (error) {
      console.error('Error fetching diagnoses:', error);
    }
  },
  
  methods: {
    view(id) {
      this.$router.push(`/admin/diagnoses/${id}`);
    },
  },
};
</script>
  
<style>
  table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 20px;
  }

  th, td {
    border: 1px solid #ddd;
    padding: 8px;
    text-align: left;
  }

  th {
    background-color: #f2f2f2;
  }

  .action-btn {
    margin-right: 5px;
    padding: 3px 8px;
    cursor: pointer;
    border: none;
    border-radius: 4px;
  }

  .view {
    background-color: green;
    color: white;
  }

  .edit {
    background-color: yellow;
    color: #333;
  }

  .create {
    margin-top: 10px;
    background-color: blue;
    color: white;
  }

  .delete {
    background-color: red;
    color: white;
  }
</style>