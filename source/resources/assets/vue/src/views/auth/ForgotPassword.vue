<template>
  <main>
    <NavigationBar />
    <div class="pt-16 px-6 pb-6">
      <h1 class="text-5xl text-center font-bold text-gray-900 mb-4">Forgot password?</h1>
      <p class="text-center text-gray-900">
        Click the link in your email inbox to get access again!
      </p>
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
        <p v-if="isSubmitting && !hasError" class="text-red-700 ml-2 mb-2">
          We sent reset link. Please check your inbox.
        </p>
        <!-- eslint-disable-next-line -->
        <button class="bg-royal-blue p-4 text-white w-full font-bold rounded-md mt-4 disabled:opacity-50 disabled:pointer-events-none"
          :disabled="isSubmitting">
          Send me reset link
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
      forgotPassword: 'auth/forgotPassword',
      clearError: 'clearError',
      clearValidationErrors: 'clearValidationErrors',
    }),
  },
})

export default class ForgotPassword extends Vue {
  private isSubmitting = false;

  private form: Record<string, string> = {
    email: '',
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
      await (this as any).forgotPassword(this.form);

      this.isSubmitting = false;

      // this.$router.replace({ name: 'auth.reset-password' });
    } catch (e) {
      this.isSubmitting = false;
    }
  }
}
</script>
