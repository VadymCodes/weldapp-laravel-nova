<template>
  <main>
    <NavigationBar />
    <div class="text-center mt-24">
      <h2 v-if="error" class="text-red-500 text-2xl">The link is invalid</h2>
    </div>
  </main>
</template>

<script>
import axios from 'axios';
import NavigationBar from '@/components/global/NavigationBar.vue';

export default {
  components: {
    NavigationBar,
  },
  data() {
    return {
      error: false
    }
  },
  mounted() {
    let that = this;
    axios.get('api/v1/email/verify/' + this.$route.params.id, {
      params: {
        expires: this.$route.query.expires,
        signature: this.$route.query.signature
      }
    })
    .then(function (response) {
      if (response.data.status == 'verification.verified') {
        that.error = false;
        that.$store.dispatch('auth/user');
        that.$router.replace({ name: 'home' });
      } else {
        that.error = true
      }
    })
    .catch(function (error) {
      that.error = error;
    });
  }

}
</script>