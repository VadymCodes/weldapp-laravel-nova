<template>
  <main>
    <NavigationBar />
    <h1 class="my-6 px-6 text-2xl font-bold text-gray-900">Create a job</h1>
    <form @submit.prevent="submit" class="px-6 pb-6">
      <label class="block mb-2">
        <span class="text-gray-700"
          :class="{ 'text-red-700': validationErrors.category_id }">
          Category
        </span>
        <select class="form-select mt-1 block w-full"
          :class="{ 'border-red-700': validationErrors.category_id }"
          v-model="form.category_id">
          <option value="" disabled selected hidden>Choose a category</option>
          <option v-for="category in categories"
            :key="category.id"
            :value="category.id">
            {{ category.name }}
        </option>
        </select>
      </label>
      <p v-if="validationErrors.category_id" class="text-red-700 ml-2 mb-2">
        {{ validationErrors.category_id[0] }}
      </p>
      <label class="block mt-4 mb-2">
        <span class="text-gray-700"
          :class="{ 'text-red-700': validationErrors.name }">Name</span>
        <input type="text"
          :class="{ 'border-red-700': validationErrors.name }"
          v-model="form.name"
          class="form-input mt-1 block w-full"
          placeholder="What is the problem?">
      </label>
      <p v-if="validationErrors.name" class="text-red-700 ml-2 mb-2">
        {{ validationErrors.name[0] }}
      </p>
      <ResizingTextarea class="mt-4 mb-2"
        :labelClass="validationErrors.description ? 'text-red-700' : ''"
        :textareaClass="validationErrors.description ? 'border-red-700' : ''"
        v-model="form.description"
        label="Description"
        placeholder="Explain the problem..."
      />
      <p v-if="validationErrors.description" class="text-red-700 ml-2 mb-2">
        {{ validationErrors.description[0] }}
      </p>
      <label class="block mt-4 mb-2">
        <span class="text-gray-700"
          :class="{ 'text-red-700': validationErrors.vehicle }">Vehicle</span>
        <input type="text"
          :class="{ 'border-red-700': validationErrors.vehicle }"
          v-model="form.vehicle"
          class="form-input mt-1 block w-full"
          placeholder="2006 Ford Mondeo">
      </label>
      <p v-if="validationErrors.vehicle" class="text-red-700 ml-2 mb-2">
        {{ validationErrors.vehicle[0] }}
      </p>
      <!-- eslint-disable-next-line -->
      <button class="bg-royal-blue p-4 text-white w-full font-bold rounded-md mt-4 disabled:opacity-50 disabled:pointer-events-none" :disabled="isSubmitting">
        Create job
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
export default class CreateJob extends Vue {
  private isSubmitting = false;

  private categories = [];

  private form: Record<string, string> = {
    // eslint-disable-next-line @typescript-eslint/camelcase
    category_id: '',
    name: '',
    description: '',
    vehicle: '',
  };

  created() {
    this.getCategories();
  }

  private getCategories() {
    axios.get('api/v1/categories')
      .then(({ data }) => {
        this.categories = data.data;
      });
  }

  private setIsSubmitting(isSubmitting: boolean): void {
    this.isSubmitting = isSubmitting;
  }

  private clearErrors(): void {
    // eslint-disable-next-line @typescript-eslint/no-explicit-any
    (this as any).clearValidationErrors();
  }

  private async submit() {
    this.setIsSubmitting(true);
    this.clearErrors();

    const user = this.$store.getters['auth/user'];

    // eslint-disable-next-line @typescript-eslint/no-explicit-any
    axios.post('api/v1/drivers/jobs', { ...this.form })
      // eslint-disable-next-line @typescript-eslint/no-explicit-any
      .then(() => {
        this.runAnalytics();
        this.$router.replace(`/drivers/${user.id}/profile`);
      })
      .catch(() => {
        this.setIsSubmitting(false);
        Vue.$toast.error(this.$store.getters['error']);
      });
  }

  private runAnalytics() {
    let data = {
      title: this.form.name,
      description: this.form.description,
      vehicle: this.form.vehicle
    };

    this.$mixpanel.track('Create Job', data);
    this.$gtag.event('Create Job', data);
    fbq('track', 'Create Job', data);
  }
}
</script>
