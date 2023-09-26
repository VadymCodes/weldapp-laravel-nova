<template>
  <main>
    <NavigationBar />
    <div v-if="latitude && longitude && !hasError"
      class="flex flex-row items-end py-4 px-6 bg-gray-100">
      <label class="block flex-1">
        <span class="text-gray-700">Filter Distance</span>
        <select class="form-select mt-1 block w-full" v-model="filterDistance">
          <option selected :value="1609">1 mile</option>
          <option :value="8046">5 miles</option>
          <option :value="16093">10 miles</option>
          <option :value="40234">25 miles</option>
        </select>
      </label>
    </div>
    <template v-if="authenticated && user.role === 'mechanic'"></template>
      <ul v-else
        class="flex mt-4 mx-6 mb-6 p-1 bg-gray-100 rounded-lg">
        <li class="flex-1">
          <!-- eslint-disable-next-line  -->
          <button class="w-full p-2 font-bold text-gray-900"
            title="Mechanic's Services" 
            :class="{ 'bg-white rounded-md shadow-sm ': isServicesTab  }"
            @click.prevent="switchTab(0)">Services</button>
        </li>
        <li class="flex-1">
          <button class="w-full p-2 font-bold text-gray-900"
            title="Mechanics" 
            :class="{ 'bg-white rounded-md shadow-sm ': isDirectoryTab  }"
            @click.prevent="switchTab(1)">Mechanics</button>
        </li>
      </ul>
    <template v-if="latitude && longitude && !hasError">
      <template v-if="authenticated && user.role === 'mechanic'">
        <MechanicJobsTab :searchClient="searchClient"
          :location="locationForAlgolia"
          :radius="filterDistance"
          index="jobs" />
      </template>
      <template v-else>
        <DriverServicesTab v-if="isServicesTab"
          :searchClient="searchClient"
          :location="locationForAlgolia"
          :radius="filterDistance"
          index="services" />
        <DriverDirectoryTab v-else
          :searchClient="searchClient"
          :location="locationForAlgolia"
          :radius="filterDistance"
          index="users" />
      </template>
    </template>
    <div v-if="hasError">
      <h2 class="px-6 mb-2 font-bold text-2xl text-gray-900">Help us out here...</h2>
      <p class="px-6 leading-relaxed text text-gray-700">{{ error }}</p>
    </div>
    <!-- eslint-disable-next-line  -->
    <router-link class="flex justify-center items-center fixed bottom-right w-16 h-16 text-4xl bg-royal-blue shadow-custom rounded-full text-white font-medium z-10 mb-4"
      :to="{ name: 'driver.job.create' }"
      v-if="authenticated && user.role === 'driver'">
      +
    </router-link>
    <loading-overlay v-if="!authenticated && !loaded && hasGeoPermission"/>
  </main>
</template>

<script lang="ts">
import algoliasearch from 'algoliasearch/lite';
import { mapGetters, mapActions } from 'vuex';
import { Component, Vue } from 'vue-property-decorator';

import NavigationBar from '@/components/global/NavigationBar.vue';
import LoadingOverlay from '@/components/global/LoadingOverlay.vue';
import DriverServicesTab from './DriverServicesTab.vue';
import DriverDirectoryTab from './DriverDirectoryTab.vue';
import MechanicJobsTab from './MechanicJobsTab.vue';

@Component({
  components: {
    NavigationBar,
    LoadingOverlay,
    DriverServicesTab,
    DriverDirectoryTab,
    MechanicJobsTab,
  },
  computed: {
    ...mapGetters({
      authenticated: 'auth/authenticated',
      user: 'auth/user',
      hasError: 'hasError',
      error: 'error',
      loaded: 'loaded',
    }),
  },
  methods: {
    ...mapActions({
      setError: 'setError',
      clearError: 'clearError',
    }),
  },
  watch: {
    // eslint-disable-next-line @typescript-eslint/no-explicit-any
    filterDistance(val: any) {
      if (val) {
        this.$store.dispatch('clearLoaded');

        // Analytics
        let distance = '';
        if (val == 8046) {
          distance = '5 miles';
        } else if(val == 16093) {
          distance = '10 miles';
        } else if (val == 40234) {
          distance = '25 miles';
        }

        if (distance) {
          this.$mixpanel.track('Distance Filter', { distance: distance });
          this.$gtag.event('Distance Filter', { distance: distance });
          fbq('track', 'Distance Filter', { distance: distance });
        }
      }
    },
  },
})
export default class Home extends Vue {
  private isServicesTab = true;

  private isDirectoryTab = false;

  private hasGeoPermission = false;

  public filterDistance = 1609;

  public searchClient = algoliasearch(
    process.env.MIX_VUE_APP_ALGOLIA_ID,
    process.env.MIX_VUE_APP_ALGOLIA_SECRET_KEY,
  );

  private latitude: number|null = null;

  private longitude: number|null = null;

  created() {
    const query = this.$route.query;
    if (query.token) {
      localStorage.setItem('token', String(query.token));
      this.$router.replace({ name: 'home' });
      this.$store.dispatch('auth/user');
    }
  }

  mounted() {
    this.getLocation();
  }

  public get locationForAlgolia(): string {
    return `${this.latitude},${this.longitude}`;
  }

  private switchTab(tab: number) {
    // this.filterDistance = 1609;

    if (tab === 0) {
      this.isServicesTab = true;
      this.isDirectoryTab = false;

      return;
    }

    this.isServicesTab = false;
    this.isDirectoryTab = true;

    this.runAnalytics();
  }

  // eslint-disable-next-line class-methods-use-this
  private getLocation() {
    navigator.geolocation.getCurrentPosition(
      (position) => {
        this.hasGeoPermission = true;
        this.latitude = position.coords.latitude;
        this.longitude = position.coords.longitude;
        // this.latitude = 51.57068280;
        // this.longitude = -0.08915170;
      },
      // eslint-disable-next-line @typescript-eslint/no-explicit-any
      (err) => {
        this.hasGeoPermission = false;
        (this as any).setError('Please give the browser the permission to access your location so you can see the feed.')
      },
    );
  }

  private runAnalytics() {
    if (this.isServicesTab) {
      this.$mixpanel.track('Service Tab');
      this.$gtag.event('Service Tab');
      fbq('track', 'Service Tab');
    } else {
      this.$mixpanel.track('Mechanic Tab');
      this.$gtag.event('Mechanic Tab');
      fbq('track', 'Mechanic Tab');
    }
  }
}
</script>

<style scoped>
  .bottom-right {
    bottom: 1.5rem;
    right: 1.5rem;
  }
</style>
