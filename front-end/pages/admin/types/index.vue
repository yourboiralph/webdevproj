<template>
  <div>
    <p>Depression Types</p>

    <button class="action-btn create" @click="create()">Create</button>
    <table>
      <thead>
        <tr>
          <th>Type</th>
          <th>Message</th>
          <th>Range Start</th>
          <th>Range End</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="t in data.depressionTypes" :key="t.id">
          <td>{{ t.type }}</td>
          <td>{{ t.message }}</td>
          <td>{{ t.scoreRangeStart }}</td>
          <td>{{ t.scoreRangeEnd }}</td>
          <td>
            <button class="action-btn view" @click="view(t.id)">View</button>
            <button class="action-btn edit" @click="edit(t.id)">Edit</button>
            <button class="action-btn delete" @click="deleteType(t.id)">Delete</button>
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
      const { data } = await useFetch('http://127.0.0.1:8000/api/depression-types');

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
      this.$router.push(`/admin/types/${id}`);
    },

    edit(adminId) {
      this.$router.push(`/admin/types/edit/${adminId}`);
    },

    create() {
      this.$router.push(`/admin/types/create`);
    },

    async deleteType(id) {
      try {
        const response = await fetch(`http://127.0.0.1:8000/api/depression-types/${id}/1/delete`, {
          method: 'DELETE',
        });

        if (response.ok) {
          alert('Deleted Succesfully');
          window.location.reload();
        } else {
          console.error('Failed to delete question:', response.statusText);
        }
      } catch (error) {
        console.error('Error deleting question:', error);
      }
    },
  },
};
</script>

<style>
/* Add your table styles here if needed */
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

/* Add the following style for limiting the maximum width of the "Message" column */
td:nth-child(2) {
  max-width: 200px; /* You can adjust the value based on your preference */
  overflow: hidden;
  text-overflow: ellipsis;
  white-space: nowrap;
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
