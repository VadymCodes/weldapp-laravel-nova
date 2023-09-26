<template>
  <main>
    <NavigationBar />
    <div class="pt-16 px-6 pb-6">
      <h1 class="text-5xl text-center font-bold text-gray-900 mb-4">You're back 🎉</h1>
      <p class="text-center text-gray-900">Login to access everything Weld!</p>
      <hr class="mt-8 mb-12 border border-royal-blue bg-royal-blue" />

      <!-- Social Login -->
      <button class="bg-blue-400 p-4 text-white w-full font-bold rounded-md disabled:opacity-50 disabled:pointer-events-none" @click="socialLogin('google')">
        <img class="inline-block" 
          src="@/assets/images/google.png"
          width="20">
        Login with Google
      </button>
      <button class="bg-indigo-800 p-4 text-white w-full font-bold rounded-md mt-4 mb-6 disabled:opacity-50 disabled:pointer-events-none" @click="socialLogin('facebook')">
        <font-awesome-icon :icon="['fab', 'facebook']" size="lg" class="text-white cursor-pointer"/>
        Login with Facebook
      </button>

      <form>
        <!-- eslint-disable-next-line  -->
        <input class="form-input block w-full mb-2 border-2 focus:border-royal-blue focus:outline-none focus:shadow-none"
          :class="{ 'border-red-700': validationErrors.email || hasError }"
          type="email"
          v-model="form.email"
          placeholder="Email address">
        <p v-if="validationErrors.email || hasError" class="text-red-700 ml-2 mb-2">
          <template v-if="hasError">{{ error }}</template>
          <template v-else>{{ validationErrors.email[0] }}</template>
        </p>
        <!-- eslint-disable-next-line -->
        <input class="form-input block w-full mb-2 mt-2 border-2 focus:border-royal-blue focus:outline-none focus:shadow-none"
          :class="{ 'border-red-700': validationErrors.password || hasError }"
          type="password"
          v-model="form.password"
          placeholder="Password">
        <p v-if="validationErrors.password" class="text-red-700 ml-2 mb-2">
          {{ validationErrors.password[0] }}
        </p>
        <!-- eslint-disable-next-line -->
        <button class="bg-royal-blue p-4 text-white w-full font-bold rounded-md mt-4 disabled:opacity-50 disabled:pointer-events-none"
          @click="submit"
          :disabled="isSubmitting">
          Login
        </button>
        <router-link class="text-mg font-bold text-gray-900 float-right mt-2"
          :to="{ name: 'auth.forgot-password' }"
        >Forgot password?</router-link>
      </form>
    </div>
    <loading-overlay v-if="isSubmitting"/>
  </main>
</template>

<script lang="ts">
import axios from 'axios';
import { mapGetters, mapActions } from 'vuex';
import { Component, Vue } from 'vue-property-decorator';

import NavigationBar from '@/components/global/NavigationBar.vue';
import LoadingOverlay from '@/components/global/LoadingOverlay.vue';

@Component({
  components: {
    NavigationBar,
    LoadingOverlay,
  },
  computed: {
    ...mapGetters({
      hasError: 'hasError',
      error: 'error',
      validationErrors: 'validationErrors',
    }),
  },
  methods: {
    ...mapActions({
      login: 'auth/login',
      clearError: 'clearError',
      clearValidationErrors: 'clearValidationErrors',
    }),
  },
})
export default class Login extends Vue {
  private isSubmitting = false;

  // private error: any;

  private form: Record<string, string> = {
    email: '',
    password: '',
  };

  public async submit() {
    if (this.isSubmitting === true) {
      return;
    }

    this.isSubmitting = true;

    // eslint-disable-next-line @typescript-eslint/no-explicit-any
    (this as any).clearError();
    // eslint-disable-next-line @typescript-eslint/no-explicit-any
    (this as any).clearValidationErrors();

    try {
      // eslint-disable-next-line @typescript-eslint/no-explicit-any
      await (this as any).login(this.form);

      this.isSubmitting = false;

      this.$router.replace({ name: 'home' });
    } catch (e) {
      this.isSubmitting = false;
    }
  }

  public socialLogin(provider: number){
    this.isSubmitting = true;
    // this.error = {};
    axios.get(`/api/v1/social/${provider}`)
      .then((response) => {
        if(response.data.error){
            // this.error = response.data.error;
        } else if(response.data.redirectUrl){
            window.location.href = response.data.redirectUrl;
        }
      })
      .catch((err) => {
        if(err.response.data.error){
            // this.error = err.response.data.error;
        }
        this.isSubmitting = false;
      });
      this.isSubmitting = false;
  }
}
</script>
