<template>
  <div class="bg-green-500 py-4 px-3 text-white">
    <div v-if="!verified">
      <span>Please verify your mobile number.</span>
      <div v-if="isSubmitted">
        <input type="text" class="bg-white text-green-500 py-2 px-4 rounded" v-model="code">
        <button class="bg-white hover:bg-blue-700 text-green-500 py-2 px-4 rounded" v-on:click="verify">Verify</button>
      </div>
      <button v-else class="bg-white hover:bg-blue-700 text-green-500 py-2 px-4 rounded" v-on:click="send">Send code</button>
    </div>
    <p v-if="message">{{ message }}</p>
  </div>
</template>

<script>
import axios from 'axios';

export default {
  data() {
    return {
      code: null,
      isSubmitted: false,
      message: '',
      verified: false,
    }
  },
  methods: {
    send() {
      let that = this;
      const user = this.$store.getters['auth/user'];
      axios.get('api/v1/phone/send')
      .then(function (response) {
        if (response.data.status == 'verification.sent') {
          that.isSubmitted = true;
          that.message = 'We sent 6-digital code to your mobile.';
        } else {
          that.isSubmitted = false;
        }
      })
      .catch(function (error) {
        that.isSubmitted = false;
      });
    },
    verify() {
      let that = this;
      const user = this.$store.getters['auth/user'];
      axios.get('api/v1/phone/verify', {
        params: {
          code: that.code,
        }
      })
      .then(function (response) {
        if (response.data.status == 'verification.verified') {
          that.message = 'Your phone number is verified successfully';
          that.verified = true;
          setTimeout(() => {
            that.$store.dispatch('auth/user');
          }, 5000);
        } else {
          that.verified = false;
          that.message = 'Your verification code is invalid';
        }
      })
      .catch(function (error) {
        that.verified = false;
      });
    }
  }
}
</script>