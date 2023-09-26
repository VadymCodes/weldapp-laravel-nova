<template>
  <main>
    <NavigationBar />
    <div class="pt-16 px-6 pb-6">
      <h1 class="text-5xl text-center font-bold text-gray-900 mb-4">Update password</h1>
      <hr class="mt-8 mb-12 border border-royal-blue bg-royal-blue" />
      <form @submit.prevent="submit">
        <!-- eslint-disable-next-line -->
        <input class="form-input block w-full mb-2 mt-2 border-2 focus:border-royal-blue focus:outline-none focus:shadow-none"
          :class="{ 'border-red-700': validationErrors.current_password || hasError }"
          type="password"
          v-model="form.currentPassword"
          placeholder="Current Password">
        <p v-if="validationErrors.current_password" class="text-red-700 ml-2 mb-2">
          {{ validationErrors.current_password[0] }}
        </p>
        <!-- eslint-disable-next-line -->
        <input class="form-input block w-full mb-2 mt-2 border-2 focus:border-royal-blue focus:outline-none focus:shadow-none"
          :class="{ 'border-red-700': validationErrors.password || hasError }"
          type="password"
          v-model="form.password"
          placeholder="New Password">
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
          Update password
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
      updatePassword: 'auth/updatePassword',
      clearError: 'clearError',
      clearValidationErrors: 'clearValidationErrors',
    }),
  },
})

export default class UpdatePassword extends Vue {
  private isSubmitting = false;

  private form: Record<string, string> = {
    currentPassword: '',
    password: '',
    confirmPassword: '',
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
      await (this as any).updatePassword({
        current_password: this.form.currentPassword,
        password: this.form.password,
        /* eslint-disable @typescript-eslint/camelcase */
        password_confirmation: this.form.confirmPassword,
      });

      this.isSubmitting = false;

      Vue.$toast.open('Password is updated successfully!');

      this.$router.go(-1);
    } catch (e) {
      this.isSubmitting = false;
    }
  }
}
</script>
