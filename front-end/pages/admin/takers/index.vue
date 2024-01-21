<template>
    <div>
      <p>Takers List</p>
      <button class="action-btn create" @click="create()">Create</button>
      <table>
        <thead>
          <tr>
            <th>Name</th>
            <th>Age</th>
            <th>Email</th>
            <th>Verification Status</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="t in data.takers" :key="t.id">
            <td>{{ `${t.first_name} ${t.last_name}` }}</td>
            <td>{{ t.age }}</td>
            <td>{{ t.email }}</td>
            <td>{{ t.is_verified ? 'Verified' : 'Not Verified' }}</td>
            <td>
              <button class="action-btn view" @click="view(t.id)">View</button>
              <button class="action-btn edit" @click="edit(t.id)">Edit</button>
              <button class="action-btn delete" @click="deleteTaker(t.id)">Delete</button>
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
        const { data } = await useFetch('http://127.0.0.1:8000/api/takers/');
  
        definePageMeta({
          layout: 'admin-layout',
        });
  
        return { data };
      } catch (error) {
        console.error('Error fetching takers:', error);
      }
    },

    methods: {
    view(takerID) {
      this.$router.push(`/admin/takers/${takerID}`);
    },

    edit(adminId) {
      this.$router.push(`/admin/takers/edit/${adminId}`);
    },

    create() {
      this.$router.push(`/admin/takers/create`);
    },

    async deleteTaker(id) {
      try {
        const response = await fetch(`http://127.0.0.1:8000/api/takers/${id}/delete`, {
          method: 'DELETE',
        });

        if (response.ok) {
          alert('Deleted Succesfully');
          window.location.reload();
        } else {
          console.error('Failed to delete taker:', response.statusText);
        }
      } catch (error) {
        console.error('Error deleting taker:', error);
      }
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

  .delete {
    background-color: red;
    color: white;
  }
</style>