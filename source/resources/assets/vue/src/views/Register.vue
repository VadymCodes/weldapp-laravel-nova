<template>
  <main>
    <NavigationBar />
    <div v-if="!isSubmitting" class="pt-16 px-6 pb-6">
      <h1 class="text-5xl text-center font-bold text-gray-900 mb-4">Hi there 👋</h1>
      <!-- eslint-disable-next-line  -->
      <p class="text-center text-gray-900">Get started by filling in the form below.</p>
      <hr class="mt-8 mb-12 border border-royal-blue bg-royal-blue" />

      <!-- Social Login -->
      <button class="bg-blue-400 p-4 text-white w-full font-bold rounded-md mt-2 disabled:opacity-50 disabled:pointer-events-none" @click="socialSignup('google')">
        <img class="inline-block" 
          src="@/assets/images/google.png"
          width="20">
        Register with Google
      </button>
      <button class="bg-indigo-800 p-4 text-white w-full font-bold rounded-md mt-4 mb-6 disabled:opacity-50 disabled:pointer-events-none" @click="socialSignup('facebook')">
        <font-awesome-icon :icon="['fab', 'facebook']" size="lg" class="text-white cursor-pointer"/>
        Register with Facebook
      </button>

      <form @submit.prevent="submit">
        <div class="mt-2 flex">
          <!-- eslint-disable-next-line  -->
          <button class="bg-white border-2 border-royal-blue py-2  w-full font-bold border-r-0 rounded-l-md"
            :class="{ 'bg-royal-blue text-white': isDriver, 'text-royal-blue': !isDriver }"
            @click.prevent="setIsDriver">Driver</button>
          <!-- eslint-disable-next-line  -->
          <button class="bg-white border-2 border-royal-blue py-2 w-full font-bold border-l-0 rounded-r-md"
            :class="{ 'bg-royal-blue text-white': !isDriver, 'text-royal-blue': isDriver }"
            @click.prevent="setIsDriver">Mechanic</button>
        </div>
        <!-- eslint-disable-next-line  -->
        <input class="form-input block w-full mt-4 mb-2 border-2 focus:border-royal-blue focus:outline-none focus:shadow-none"
          :class="{ 'border-red-700': validationErrors.name }"
          type="text"
          v-model="form.name"
          :placeholder="isDriver ? 'Name' : 'Company name'">
        <p v-if="validationErrors.name" class="text-red-700 ml-2 mb-2">
          {{ validationErrors.name[0] }}
        </p>
        <!-- eslint-disable-next-line  -->
        <input class="form-input block w-full mb-2 border-2 focus:border-royal-blue focus:outline-none focus:shadow-none"
          :class="{ 'border-red-700': validationErrors.email }"
          type="email"
          v-model="form.email"
          placeholder="Email address">
        <p v-if="validationErrors.email" class="text-red-700 ml-2 mb-2">
          {{ validationErrors.email[0] }}
        </p>
        <!-- eslint-disable-next-line  -->
        <input class="form-input block w-full mb-2 border-2 focus:border-royal-blue focus:outline-none focus:shadow-none"
          :class="{ 'border-red-700': validationErrors.phone }"
          type="tel"
          v-model="form.phone"
          placeholder="Phone number">
        <p v-if="validationErrors.phone" class="text-red-700 ml-2 mb-2">
          {{ validationErrors.phone[0] }}
        </p>
        <GmapAutocomplete @place_changed="setPlace">
          <template v-slot:input="slotProps">
            <!-- eslint-disable-next-line -->
            <input class="form-input block w-full mb-2 mt-2 border-2 focus:border-royal-blue focus:outline-none focus:shadow-none"
              :class="{ 'border-red-700': validationErrors.place_id }"
              ref="input"
              type="text"
              :placeholder="isDriver ? 'Address' : 'Company address'"
              v-on:listeners="slotProps.listeners"
              v-on:attrs="slotProps.attrs"
            />
          </template>
        </GmapAutocomplete>
        <p v-if="validationErrors.place_id" class="text-red-700 ml-2 mb-2">
          {{ validationErrors.place_id[0] }}
        </p>
        <p class="px-2 text-sm text-gray-600">
          <!-- eslint-disable-next-line -->
          <template v-if="isDriver">We need your address to help connect you to local mechanics.</template>
          <!-- eslint-disable-next-line -->
          <template v-else>Enter your companies address or find it by typing your companies name.</template>
        </p>
        <!-- eslint-disable-next-line -->
        <input class="form-input block w-full mb-2 mt-2 border-2 focus:border-royal-blue focus:outline-none focus:shadow-none"
          :class="{ 'border-red-700': validationErrors.password || hasError }"
          type="password"
          v-model="form.password"
          v-show="!isSocialSignup"
          placeholder="Password">
        <p v-if="validationErrors.password" class="text-red-700 ml-2 mb-2">
          {{ validationErrors.password[0] }}
        </p>
        <!-- eslint-disable-next-line -->
        <button class="bg-royal-blue p-4 text-white w-full font-bold rounded-md mt-4 disabled:opacity-50 disabled:pointer-events-none"
          :disabled="isSubmitting">
          Register
        </button>
      </form>

      <div class="flex mt-6">
        <label class="flex">
          <!-- eslint-disable-next-line -->
          <input type="checkbox" class="form-checkbox border-blue-700 border-2 text-royal-blue mt-1" v-model="isChecked">
          <!-- eslint-disable-next-line -->
          <p class="ml-2 text-gray-900">By Registering, you agree to the <a href="/terms-of-use" class="underline text-royal-blue" target="_blank">Terms of Service</a> and <a href="/privacy" class="underline text-royal-blue"  target="_blank">Privacy Policy</a>.</p>
        </label>
      </div>
    </div>
    <loading-overlay v-else/>
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
      clearError: 'clearError',
      clearValidationErrors: 'clearValidationErrors',
      register: 'auth/register',
      setRegRole: 'auth/setRegRole',
    }),
  },
})
export default class Register extends Vue {
  private isSubmitting = false;

  private isDriver = true;

  private isChecked = false;

  private isSocialSignup = false;

  private socialProvider = '';

  private form: Record<string, boolean|string> = {
    name: '',
    email: '',
    password: '',
  };

  private place: Record<string, string|number|null> = {
    // eslint-disable-next-line @typescript-eslint/camelcase
    lat: null, lng: null, address: '', place_id: '',
  };

  mounted() {
    const query = this.$route.query;
    if (query && query.name && query.email && query.id) {
      this.form = {
        name: String(query.name),
        email: String(query.email),
        password: String(query.email) + String(query.id),
      }

      this.isSocialSignup = true;
    }
  }

  private clearErrors(): void {
    // eslint-disable-next-line @typescript-eslint/no-explicit-any
    (this as any).clearError();
    // eslint-disable-next-line @typescript-eslint/no-explicit-any
    (this as any).clearValidationErrors();
  }

  public setIsSubmitting(isSubmitting: boolean): void {
    this.isSubmitting = isSubmitting;
  }

  // eslint-disable-next-line @typescript-eslint/no-explicit-any
  public setPlace(place: Record<string, any>): void {
    const {
      // eslint-disable-next-line @typescript-eslint/camelcase
      geometry: { location }, formatted_address: address, place_id,
    } = place;

    this.place = {
      // eslint-disable-next-line @typescript-eslint/camelcase
      lat: location.lat(), lng: location.lng(), address, place_id,
    };
  }

  public setIsDriver() {
    this.clearErrors();

    this.isDriver = !this.isDriver;

    // (this as any).setRegRole(this.isDriver? 'driver': 'mechanic');
    this.isChecked = false;
  }

  public async submit() {
    if (!this.isChecked) {
      return;
    }

    this.setIsSubmitting(true);
    this.clearErrors();

    // eslint-disable-next-line @typescript-eslint/no-explicit-any
    (this as any).register({ ...this.form, driver: this.isDriver, ...this.place })
      .then(() => {
        this.runAnalytics();
        this.$router.replace({ name: 'home' });
      })
      .catch(() => this.setIsSubmitting(false));
  }

  public socialSignup(provider: string){
    this.isSubmitting = true;
    this.socialProvider = provider;
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

  private runAnalytics() {
    let data = {
      method: 'form'
    };

    if (this.isSocialSignup) {
      data.method = this.socialProvider;
    }

    this.$mixpanel.track('Sign Up', data);
    this.$gtag.event('Sign Up', data);
    fbq('track', 'Sign Up', data);
  }
}
</script>

<style>
  .pac-container {
    border-top: none;
    @apply shadow-md;
  }

  .pac-item > span:last-child {
    @apply text-gray-600;
  }

  .pac-item-query {
    @apply text-gray-900;
  }

  .pac-matched {
    @apply text-royal-blue;
  }

  .pac-logo::after {
    display: none !important;
    background-image: none !important;
  }

  .pac-item {
    line-height: unset;
    @apply flex items-center py-3 px-2;
  }

    .pac-item:first-child {
      border-top: none;
  }

  .pac-icon {
    vertical-align: unset;
    @apply mt-0;
  }
</style>
