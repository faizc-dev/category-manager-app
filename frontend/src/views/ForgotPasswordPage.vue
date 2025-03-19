<template>
  <div class="container mt-5">
    <h2>Forgot Password</h2>
    <form @submit.prevent="handleReset">
      <div class="mb-3">
        <label class="form-label">Email</label>
        <input type="email" v-model="email" class="form-control" required>
      </div>
      <button type="submit" class="btn btn-warning">Reset Password</button>
    </form>
  </div>
</template>

<script>
import { ref } from 'vue';

export default {
  setup() {
    const email = ref('');

    const handleReset = () => {
      alert('Password reset link sent to ' + email.value);
    };

    return { email, handleReset };
  }
};
</script>