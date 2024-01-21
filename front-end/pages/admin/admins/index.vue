<template>
  <div>
    <p>Admins List</p>
    <button class="action-btn create" @click="create()">Create</button>

    <template v-if="data.admins.length > 0">
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
          <template v-for="t in data.admins" :key="t.id">
            <tr v-if="t.id !== 1">
              <td>{{ `${t.first_name} ${t.last_name}` }}</td>
              <td>{{ t.age }}</td>
              <td>{{ t.email }}</td>
              <td>{{ t.is_verified ? 'Verified' : 'Not Verified' }}</td>
              <td>
                <button class="action-btn view" @click="view(t.id)">View</button>
                <button class="action-btn edit" @click="edit(t.id)">Edit</button>
                <button class="action-btn delete" @click="deleteAdmin(t.id)">Delete</button>
              </td>
            </tr>
          </template>
        </tbody>
      </table>
    </template>

    <template v-else>
      <p>No admins available.</p>
    </template>
  </div>
</template>

<script>
export default { 
  async setup() {
    try {
      const { data } = await useFetch('http://127.0.0.1:8000/api/admins/');
    
      definePageMeta({
        layout: 'admin-layout',
      }); 
    
      return { data };
    } catch (error) {
      console.error('Error fetching admins:', error);
    }
  },
  
  methods: {
    view(adminId) {
      this.$router.push(`/admin/admins/${adminId}`);
    },

    edit(adminId) {
      this.$router.push(`/admin/admins/edit/${adminId}`);
    },

    create() {
      this.$router.push(`/admin/admins/create`);
    },

    async deleteAdmin(adminId) {
      try {
        const response = await fetch(`http://127.0.0.1:8000/api/admins/${adminId}/delete`, {
          method: 'DELETE',
        });

        if (response.ok) {
          alert('Deleted Succesfully');
          window.location.reload();
        } else {
          console.error('Failed to delete administrator:', response.statusText);
        }
      } catch (error) {
        console.error('Error deleting administrator:', error);
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