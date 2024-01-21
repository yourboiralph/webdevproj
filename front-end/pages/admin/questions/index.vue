<template>
  <div>
    <p>Questions List</p>
    <table>
      <thead>
        <tr>
          <th>Question</th>
          <th>Admin</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="t in data.questions" :key="t.id">
          <td>{{ `${t.question}` }}</td>
          <td>
            <nuxt-link class="admin-link" :to="'/admin/admins/' + t.adminID">{{ `${t.adminID}` }}</nuxt-link>
          </td>
          <td>
            <button class="action-btn view" @click="view(t.id)">View</button>
            <button class="action-btn edit" @click="edit(t.id)">Edit</button>
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
      const { data } = await useFetch('http://127.0.0.1:8000/api/questions/');

      definePageMeta({
        layout: 'admin-layout',
      });

      return { data };
    } catch (error) {
      console.error('Error fetching:', error);
    }
  },

  methods: {
    view(id) {
      this.$router.push(`/admin/questions/${id}`);
    },

    edit(id) {
      this.$router.push(`/admin/questions/edit/${id}`);
    },
  },
};
</script>

<style>
.admin-link {
    color: blue; 
    cursor: pointer; 
    text-decoration: underline; 
    text-align: center;
  }

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

  .delete {
    background-color: red;
    color: white;
  }
</style>