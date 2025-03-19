<template>
  <div class="container mt-5">
    <!-- Display User Info -->
    <div class="text-left mb-4">
      <h3>{{ user?.name || "User" }}</h3>
      <img v-if="user?.photo" :src="serverUrl+'/'+user.photo" alt="Profile Photo" class="img-thumbnail" width="150">
      <p v-else>No profile photo available</p>
    </div>

    <h2>Update Profile</h2>

    <form @submit.prevent="updateProfile">
      <div class="mb-3">
        <label class="form-label">Name</label>
        <input type="text" v-model="name" class="form-control" required>
      </div>
      <div class="mb-3">
        <label class="form-label">Photo</label>
        <input type="file" class="form-control" @change="handleFileUpload">
      </div>
      <button type="submit" class="btn btn-success" :disabled="loading">
        {{ loading ? 'Updating...' : 'Update' }}
      </button>
    </form>
  </div>
</template>

<script>
import { ref, computed } from "vue";
import { useStore } from "vuex";
import api, { API_BASE_URL } from "@/api";

export default {
  setup() {
    const store = useStore();
    const name = ref("");
    const photo = ref(null);
    const photoPreview = ref(null);
    const loading = ref(false);
    const serverUrl = API_BASE_URL;
   

    const user = computed(() => store.state.user);
    const token = computed(() => store.state.token);

    // Set initial values from store
    name.value = user.value?.name || "";
    photoPreview.value = user.value?.photo || null;

    const handleFileUpload = (event) => {
      photo.value = event.target.files[0];
      photoPreview.value = URL.createObjectURL(photo.value);
    };

    const updateProfile = async () => {
      if (!name.value) {
        alert("Please enter your name.");
        return;
      }

      const formData = new FormData();
      formData.append("name", name.value);
      formData.append("email", user.value.email);
      if (photo.value) {
        formData.append("photo", photo.value);
      }

      loading.value = true;
      try {
        const response = await api.post("/update_profile.php", formData, {
          headers: {
            "Content-Type": "multipart/form-data",
            Authorization: token.value,
          },
        });

        if (response.data.success) {
          alert("Profile updated successfully!");
          store.dispatch("fetchProfiles", user.value.email);
        } else {
          alert("Error: " + response.data.error);
        }
      } catch (error) {
        console.error("Update failed:", error);
        alert("An error occurred while updating.");
      } finally {
        loading.value = false;
      }
    };

    return { user, name, photo, photoPreview, loading, serverUrl, handleFileUpload, updateProfile };
  },
};
</script>
