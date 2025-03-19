<template>
  <div class="container mt-5">
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
      <a class="navbar-brand" href="#">Vue3 App</a>
    </nav>

    <div id="carouselExampleIndicators" class="carousel slide" data-bs-ride="carousel">
      <div class="carousel-inner">
        <div class="carousel-item active">
          <img src="https://www.wonderplugin.com/wp-content/uploads/2019/05/tutorial-computer-900x288.jpg" class="d-block w-100" alt="Slide 1">
        </div>
      </div>
    </div>

    <h2 class="mt-4">Profiles</h2>
    <div class="row">
      <div class="col-md-4" v-for="profile in profiles" :key="profile.id">
        <div class="card profile-card">
          <img :src="serverUrl + '/' + profile.photo" class="card-img-top profile-img" alt="Profile Photo">
          <div class="card-body text-center">
            <h5 class="card-title">{{ profile.name }}</h5>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<style scoped>
/* Fixed Profile Card Size */
.profile-card {
  min-height: 400px; /* Ensures equal height */
  display: flex;
  flex-direction: column;
  justify-content: space-between;
}

/* Fixed Image Size */
.profile-img {
  height: 400px;
  object-fit: cover; /* Ensures image fills without distortion */
  width: 100%;
}

/* Centering Text */
.card-body {
  display: flex;
  flex-direction: column;
  align-items: center;
}
</style>

<script>
import { computed, onMounted } from 'vue';
import { useStore } from 'vuex';
import { API_BASE_URL } from "@/api";

export default {
  setup() {
    const store = useStore();
    const profiles = computed(() => store.getters.getProfiles.users);
    const serverUrl = API_BASE_URL;
    
    onMounted(() => {
      store.dispatch('fetchProfiles');
    });
    
    return { profiles, serverUrl };
  }
};
</script>
