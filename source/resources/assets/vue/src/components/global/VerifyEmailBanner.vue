<template>
  <div class="bg-green-500 py-3 px-3 text-white">
    <span>Please confirm your email by clicking the link we sent to your email</span>
    <button class="bg-white hover:bg-blue-700 text-green-500 py-2 px-4 rounded" v-on:click="resend">Resend confirmation email</button>
    <p v-if="success">We sent the link to your email. Please check your inbox.</p>
  </div>
</template>

<script>
import axios from 'axios';

export default {
  data() {
    return {
      success: false
    }
  },
  methods: {
    resend() {
      let that = this;
      const user = this.$store.getters['auth/user'];
      axios.get('api/v1/email/resend', {
        params: {
          email: user.email,
        }
      })
      .then(function (response) {
        if (response.data.status == 'verification.sent') {
          that.success = true;
        } else {
          that.success = false
        }
      })
      .catch(function (error) {
        that.success = false;
      });
    }
  }
}
</script>