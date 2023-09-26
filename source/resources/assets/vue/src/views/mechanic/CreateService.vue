<template>
  <main>
    <NavigationBar />
    <h1 class="my-6 px-6 text-2xl font-bold text-gray-900">Create a service</h1>
    <form @submit.prevent="submit" class="px-6 pb-6">
      <label class="block mb-2">
        <span class="text-gray-700"
          :class="{ 'text-red-700': validationErrors.name }">Name</span>
        <input type="text"
          :class="{ 'border-red-700': validationErrors.name }"
          v-model="form.name"
          class="form-input mt-1 block w-full"
          placeholder="MOT Service">
      </label>
      <p v-if="validationErrors.name" class="text-red-700 ml-2 mb-2">
        {{ validationErrors.name[0] }}
      </p>
      <label class="block mt-4 mb-2">
        <span class="text-gray-700"
          :class="{ 'text-red-700': validationErrors.cost }">Cost</span>
        <input type="text"
          :class="{ 'border-red-700': validationErrors.cost }"
          v-model="form.cost"
          class="form-input mt-1 block w-full"
          placeholder="Quote on inspection">
      </label>
      <p v-if="validationErrors.cost" class="text-red-700 ml-2 mb-2">
        {{ validationErrors.cost[0] }}
      </p>
      <ResizingTextarea class="mt-4 mb-2"
        :labelClass="validationErrors.description ? 'text-red-700' : ''"
        :textareaClass="validationErrors.description ? 'border-red-700' : ''"
        v-model="form.description"
        label="Description"
        placeholder="Full MOT service available at..."
      />
      <p v-if="validationErrors.description" class="text-red-700 ml-2 mb-2">
        {{ validationErrors.description[0] }}
      </p>
      <!-- eslint-disable-next-line -->
      <button class="bg-royal-blue p-4 text-white w-full font-bold rounded-md mt-4 disabled:opacity-50 disabled:pointer-events-none" :disabled="isSubmitting">
        Create service
      </button>
    </form>
  </main>
</template>

<script lang="ts">
import axios from 'axios';
import { mapGetters, mapActions } from 'vuex';
import { Component, Vue } from 'vue-property-decorator';

import NavigationBar from '@/components/global/NavigationBar.vue';
import ResizingTextarea from '@/components/global/ResizingTextarea.vue';

@Component({
  components: {
    NavigationBar,
    ResizingTextarea,
  },
  computed: {
    ...mapGetters({
      user: 'auth/user',
      validationErrors: 'validationErrors',
    }),
  },
  methods: {
    ...mapActions({
      clearValidationErrors: 'clearValidationErrors',
    }),
  },
})
export default class CreateService extends Vue {
  private isSubmitting = false;

  private form: Record<string, string> = {
    name: '',
    cost: '',
    description: '',
  };

  public setIsSubmitting(isSubmitting: boolean): void {
    this.isSubmitting = isSubmitting;
  }

  private clearErrors(): void {
    // eslint-disable-next-line @typescript-eslint/no-explicit-any
    (this as any).clearValidationErrors();
  }

  private async submit() {
    this.setIsSubmitting(true);
    this.clearErrors();

    // eslint-disable-next-line @typescript-eslint/no-explicit-any
    axios.post('api/v1/mechanics/services', { ...this.form })
      // eslint-disable-next-line @typescript-eslint/no-explicit-any
      .then(() => {
        this.runAnalytics();
        this.$router.replace({ name: 'mechanic.profile', params: { id: (this as any).user.id } });
      })
      .catch((err) => {
        this.setIsSubmitting(false);
        Vue.$toast.error(this.$store.getters['error']);
      });
  }

  private runAnalytics() {
    let data = {
      title: this.form.name,
      description: this.form.description,
      cost: this.form.cost
    };

    this.$mixpanel.track('Create Service', data);
    this.$gtag.event('Create Service', data);
    fbq('track', 'Create Service', data);
  }
}
</script>
