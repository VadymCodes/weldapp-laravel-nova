<template>
  <main>
    <NavigationBar />
    <div class="pt-16 px-6 pb-6">
      <h1 class="text-5xl text-center font-bold text-gray-900 mb-4">Reset password</h1>
      <p class="text-center text-gray-900">Please reset your password to get access again!</p>
      <hr class="mt-8 mb-12 border border-royal-blue bg-royal-blue" />
      <form @submit.prevent="submit">
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
        <input class="form-input block w-full mb-2 mt-2 border-2 focus:border-royal-blue focus:outline-none focus:shadow-none"
          :class="{ 'border-red-700': validationErrors.confirmPassword || hasError }"
          type="password"
          v-model="form.confirmPassword"
          placeholder="Confirm Password">
        <p v-if="validationErrors.confirmPassword" class="text-red-700 ml-2 mb-2">
          {{ validationErrors.confirmPassword[0] }}
        </p>
        <!-- eslint-disable-next-line -->
        <button class="bg-royal-blue p-4 text-white w-full font-bold rounded-md mt-4 disabled:opacity-50 disabled:pointer-events-none"
          :disabled="isSubmitting">
          Reset password
        </button>
      </form>
    </div>
  </main>
</template>

<script lang="ts">
import { mapGetters, mapActions } from 'vuex';
import { Component, Vue } from 'vue-property-decorator';

import NavigationBar from '@/components/global/NavigationBar.vue';

@Component({
  components: {
    NavigationBar,
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
      resetPassword: 'auth/resetPassword',
      clearError: 'clearError',
      clearValidationErrors: 'clearValidationErrors',
    }),
  },
})

export default class ResetPassword extends Vue {
  private isSubmitting = false;

  private token = this.$route.params.token;

  private form: Record<string, string> = {
    email: this.$route.params.ue,
    password: '',
    confirmPassword: '',
  };

  public async submit() {
    console.log(this.token);
    console.log(this.form.password);
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
      await (this as any).resetPassword({
        email: this.form.email,
        password: this.form.password,
        /* eslint-disable @typescript-eslint/camelcase */
        password_confirmation: this.form.confirmPassword,
        token: this.token,
      });

      this.isSubmitting = false;

      this.$router.replace({ name: 'auth.login' });
    } catch (e) {
      this.isSubmitting = false;
    }
  }
}
</script>
