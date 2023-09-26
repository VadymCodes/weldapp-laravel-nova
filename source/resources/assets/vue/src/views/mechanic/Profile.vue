<template>
  <main>
    <NavigationBar />
    <div class="flex py-8 px-6">
      <div class="w-24 h-24 bg-gray-100 rounded-full">
        <img src="@/assets/images/profile.jpeg" class="rounded-full" />
      </div>
      <div class="flex-1 pl-4">
        <!-- eslint-disable-next-line  -->
        <h1 class="mb-2 text-xl font-bold text-gray-900"
          v-if="mechanicProfile">{{ mechanicProfile.name }}</h1>
        <p class="font-medium text-gray-700"
          v-if="mechanicProfile.address">{{ mechanicProfile.address.address }}</p>
        <star-rating
          :read-only="true"
          :rounded-corners="true"
          :increment="0.1"
          :star-size="15"
          class="m-2"
          v-model="avgRating"/>
        <!-- eslint-disable-next-line  -->
        <button v-if="user.id !== mechanicProfile.id" class="flex rounded justify-center items-center px-3 py-1 bg-royal-blue shadow-custom text-white font-medium z-10 mb-4">
          <a :href="getPhoneNumber(mechanicProfile.phone)" @click="runAnalytics">Call Now</a>
        </button>
        <router-link v-if="user.id === mechanicProfile.id" class="text-mg text-royal-blue"
          :to="{ name: 'auth.update-password' }"
        >Change password</router-link>
      </div>
      <div class="flex-1">
        <badge v-if="mechanicProfile.accredited == true" />
      </div>
    </div>
    <ul class="flex mx-6 mb-6 p-1 bg-gray-100 rounded-lg" v-if="user.id === mechanicProfile.id">
      <li class="flex-1">
        <!-- eslint-disable-next-line  -->
        <button class="w-full p-2 font-bold text-gray-900"
          :class="{ 'bg-white rounded-md shadow-sm ': isServicesTab  }"
          @click.prevent="switchTab(0)">Services</button>
        </li>
      <li class="flex-1">
        <button class="w-full p-2 font-bold text-gray-900"
          :class="{ 'bg-white rounded-md shadow-sm ': isDirectoryTab  }"
          @click.prevent="switchTab(1)">Active Jobs</button>
        </li>
      <li class="flex-1">
        <button class="w-full p-2 font-bold text-gray-900"
          :class="{ 'bg-white rounded-md shadow-sm ': isPortfolioTab  }"
          @click.prevent="switchTab(2)">Job Portfolio</button>
      </li>
    </ul>
    <ul class="flex mx-6 mb-6 p-1 bg-gray-100 rounded-lg" v-else>
      <li class="flex-1">
        <!-- eslint-disable-next-line  -->
        <button class="w-full p-2 font-bold text-gray-900"
          :class="{ 'bg-white rounded-md shadow-sm ': isServicesTab  }"
          @click.prevent="switchTab(0)">Services</button>
        </li>
      <li class="flex-1">
        <button class="w-full p-2 font-bold text-gray-900"
          :class="{ 'bg-white rounded-md shadow-sm ': isPortfolioTab  }"
          @click.prevent="switchTab(2)">Job Portfolio</button>
      </li>
    </ul>
    <template v-if="isServicesTab">
      <InifiniteScroll v-if="mechanicServicesMeta.last_page"
        @inifinite-scroll:fetch="getServices"
        :items="mechanicServices"
        :lastPage="mechanicServicesMeta.last_page">
        <template v-slot:item="{ item }">
          <ServiceItem :name="item.name"
            :cost="item.cost"
            :description="item.description"
            :mechanic="item.mechanic" />
        </template>
      </InifiniteScroll>
    </template>
    <template v-if="isDirectoryTab">
      <p v-show="unfulfilledJobs.length === 0"
        class="mt-48 px-6 text-center text-gray-700 text-xl">No active jobs yet!</p>
      <InifiniteScroll v-if="unfulfilledJobsMeta.last_page"
        @inifinite-scroll:fetch="getMechanicsUnfulfilledJobs"
        :items="unfulfilledJobs"
        :lastPage="unfulfilledJobsMeta.last_page">
        <template v-slot:item="{ item }">
          <div class="mx-6 mb-8 rounded-md border-gray-300 border-2">
            <div class="flex flex-col p-4 bg-gray-100 rounded-tl-md rounded-tr-md">
              <a href="#" class="text-xl font-bold text-gray-900">{{ item.job.name }}</a>
              <!-- eslint-disable-next-line  -->
              <p class="mt-2 text-gray-700">
                Posted by <span class="font-medium">{{ item.job.user.name }}</span>
                in <span class="font-medium">{{ item.job.category.name }}</span>
              </p>
            </div>
            <!-- eslint-disable-next-line  -->
            <p class="mt-4 px-4 text-gray-700">Working on a <span class="font-medium text-royal-blue">{{ item.job.vehicle }}</span></p>
            <!-- eslint-disable-next-line  -->
            <p class="mt-4 mb-4 px-4 text-gray-900 leading-relaxed whitespace-pre-line">{{ item.job.description }}</p>
            <!-- fulfillJob -->
            <!-- eslint-disable-next-line  -->
            <button class="w-full block mt-6 p-4 text-white text-center font-bold bg-royal-blue rounded-bl-md rounded-br-md disabled:opacity-50 disabled:pointer-events-none"
              @click="fulfillJob(item.id)"
              :disabled="fulfilledJobs.includes(item.id)">
              Mark fulfilled
            </button>
          </div>
        </template>
      </InifiniteScroll>
    </template>
    <template v-if="isPortfolioTab">
      <p v-show="reviewedJobs.length === 0"
        class="mt-24 px-6 text-center text-gray-700 text-xl">No portfolio yet!</p>
      <InifiniteScroll v-if="fulfilledJobsMeta.last_page"
        @inifinite-scroll:fetch="getMechanicsFulfilledJobs"
        :items="reviewedJobs"
        :lastPage="fulfilledJobsMeta.last_page">
        <template v-slot:item="{ item }">
          <div class="mx-6 mb-8 rounded-md border-gray-300 border-2">
            <div class="flex flex-col p-4 bg-gray-100 rounded-tl-md rounded-tr-md">
              <a href="#" class="text-xl font-bold text-gray-900">{{ item.job.name }}</a>
              <!-- eslint-disable-next-line  -->
              <p class="mt-2 text-gray-700">
                Posted by <span class="font-medium">{{ item.job.user.name }}</span>
                in <span class="font-medium">{{ item.job.category.name }}</span>
              </p>
            </div>
            <!-- eslint-disable-next-line  -->
            <p class="mt-4 px-4 text-gray-700">Working on a <span class="font-medium text-royal-blue">{{ item.job.vehicle }}</span></p>
            <!-- eslint-disable-next-line  -->
            <p class="mt-4 mb-4 px-4 text-gray-900 leading-relaxed whitespace-pre-line">{{ item.job.description }}</p>
            <div class="m-3">
              <div v-if="item.mechanic_rating">
                <p class="text-gray-700">{{ item.job.user.name }}'s feedback</p>
                <star-rating
                  :read-only="true"
                  :rounded-corners="true"
                  :animate="true"
                  :star-size="20"
                  class="m-2"
                  v-model="item.mechanic_rating"/>
                <q>{{ item.mechanic_comment }}</q>
              </div>
              <p v-else class="text-gray-700">No feedback given</p>
            </div>
            <div class="m-3" v-if="user.id === mechanicProfile.id">
              <div v-if="item.driver_rating">
                <p class="text-gray-700">Your feedback</p>
                <star-rating
                  :read-only="true"
                  :rounded-corners="true"
                  :animate="true"
                  :star-size="20"
                  class="m-2"
                  v-model="item.driver_rating"/>
                <q>{{ item.driver_comment }}</q>
              </div>
              <div v-else>
                <!-- eslint-disable-next-line -->
                <star-rating
                  :star-size="20"
                  :rounded-corners="true"
                  :animate="true"
                  class="m-2"
                  v-model="item.driver_rating_new"/>
                <textarea
                  class="form-input w-full" rows="4"
                  v-model="item.driver_comment"></textarea>
                <!-- eslint-disable-next-line -->
                <button class="form-input bg-royal-blue text-white rounded-md" @click="sendFeedback(item.job.id, item.driver_rating_new, item.driver_comment)" :disabled="isSubmitting">Send feedback</button>
              </div>
            </div>
          </div>
        </template>
      </InifiniteScroll>
    </template>
    <!-- eslint-disable-next-line  -->
    <button class="flex justify-center items-center fixed bottom-right w-16 h-16 text-4xl bg-royal-blue shadow-custom rounded-full text-white font-medium z-10 mb-4"
      v-if="user.id === mechanicProfile.id"
      @click.prevent="gotoCreateService">
      +
    </button>
  </main>
</template>

<script lang="ts">
import axios from 'axios';
import { mapGetters, mapActions } from 'vuex';
import { Component, Vue } from 'vue-property-decorator';
import StarRating from 'vue-star-rating';

import NavigationBar from '@/components/global/NavigationBar.vue';
import InifiniteScroll from '@/components/global/InfiniteScroll.vue';
import Badge from '@/components/global/Badge.vue';
import ServiceItem from './ServiceItem.vue';

@Component({
  components: {
    NavigationBar,
    InifiniteScroll,
    Badge,
    ServiceItem,
    StarRating,
  },
  computed: {
    ...mapGetters({
      hasError: 'hasError',
      error: 'error',
      user: 'auth/user',
      mechanicProfile: 'mechanic/mechanicProfile',
      mechanicServices: 'mechanic/services',
      mechanicServicesMeta: 'mechanic/servicesMeta',
      unfulfilledJobs: 'job/unfulfilledJobs',
      unfulfilledJobsMeta: 'job/unfulfilledJobsMeta',
      reviewedJobs: 'mechanic/fulfilledJobs',
      fulfilledJobsMeta: 'mechanic/fulfilledJobsMeta',
    }),
  },
  methods: {
    ...mapActions({
      clearError: 'clearError',
      getMechanicProfile: 'mechanic/getMechanicProfile',
      getMechanicServices: 'mechanic/getMechanicServices',
      clearMechanicServices: 'mechanic/clearMechanicServices',
      getMechanicsUnfulfilledJobs: 'job/getUnfulfilledJobs',
      getMechanicsFulfilledJobs: 'mechanic/getMechanicFulfilledJobs',
      setLoaded: 'setLoaded',
      clearLoaded: 'clearLoaded',
    }),
  },
})
export default class Profile extends Vue {
  private isServicesTab = true;

  private isDirectoryTab = false;

  private isPortfolioTab = false;

  private fulfilledJobs: number[] = [];

  private isSubmitting = false;

  private avgRating = 0;

  async mounted() {
    // eslint-disable-next-line @typescript-eslint/no-explicit-any
    await (this as any).getMechanicProfile(this.$route.params.id);
    // eslint-disable-next-line @typescript-eslint/no-explicit-any
    await (this as any).getMechanicServices({ id: this.$route.params.id });
    // eslint-disable-next-line @typescript-eslint/no-explicit-any
    await (this as any).getMechanicsUnfulfilledJobs({ page: 1 });
    // eslint-disable-next-line @typescript-eslint/no-explicit-any
    await (this as any).getMechanicsFulfilledJobs({ id: this.$route.params.id });
    // eslint-disable-next-line @typescript-eslint/no-explicit-any
    (this as any).setLoaded();
    // eslint-disable-next-line @typescript-eslint/no-explicit-any
    (this as any).getAvgRating((this as any).reviewedJobs);
  }

  private async switchTab(tab: number) {
    switch (tab) {
      case 0:
        this.isServicesTab = true;
        this.isDirectoryTab = false;
        this.isPortfolioTab = false;

        this.runAnalyticsTab('Services');
        break;

      case 1:
        this.isServicesTab = false;
        this.isDirectoryTab = true;
        this.isPortfolioTab = false;

        this.runAnalyticsTab('Active Jobs');
        break;

      case 2:
        this.isServicesTab = false;
        this.isDirectoryTab = false;
        this.isPortfolioTab = true;

        this.runAnalyticsTab('Job Portfolio');
        if ((this as any).user.id !== (this as any).mechanicProfile.id) {
          this.runAnalyticsViewProfile();
        }
        break;

      default:
        break;
    }
  }

  private async getUnfulfilledJobs(nextPage: number) {
    // eslint-disable-next-line @typescript-eslint/no-explicit-any
    await (this as any).getMechanicsUnfulfilledJobs({ page: nextPage });
  }

  private async getServices(nextPage: number) {
    // eslint-disable-next-line @typescript-eslint/no-explicit-any
    await (this as any).getMechanicServices({
      id: this.$route.params.id,
      page: nextPage,
    });
  }

  // eslint-disable-next-line class-methods-use-this
  private fulfillJob(job: number) {
    this.fulfilledJobs.push(job);

    axios.post(`api/v1/drivers/jobs/fulfill/${job}`)
      .then(async () => {
        // eslint-disable-next-line @typescript-eslint/no-explicit-any
        await (this as any).getMechanicsUnfulfilledJobs({ page: 1 });
        // eslint-disable-next-line @typescript-eslint/no-explicit-any
        await (this as any).getMechanicsFulfilledJobs({ page: 1 });

        const index = this.fulfilledJobs.indexOf(job);
        if (index > -1) {
          this.fulfilledJobs.splice(index, 1);
        }

        // eslint-disable-next-line @typescript-eslint/no-explicit-any
        await (this as any).getMechanicsFulfilledJobs({ id: this.$route.params.id });
        this.switchTab(2);
      });
  }

  public async gotoCreateService() {
    // eslint-disable-next-line @typescript-eslint/no-explicit-any
    await (this as any).clearMechanicServices();

    this.$router.push({ name: 'mechanic.service.create' });
  }

  // eslint-disable-next-line @typescript-eslint/no-explicit-any
  private getAvgRating(jobs: any[]) {
    let rating = 0;
    if (jobs.length > 0) {
      const reviews = jobs.filter((job) => job.mechanic_rating != null);
      reviews.forEach((job) => {
        rating += job.mechanic_rating;
      });
      this.avgRating = rating / reviews.length;
    }
  }

  private sendFeedback(job: number, rating: number, comment: string) {
    this.isSubmitting = true;
    axios.post('api/v1/jobs/feedback-by-mechanic', { job, rating, comment })
      .then(async () => {
        // eslint-disable-next-line @typescript-eslint/no-explicit-any
        await (this as any).getMechanicsFulfilledJobs({ page: 1 });
        this.isSubmitting = false;
      });
  }

  private getPhoneNumber(phone: string) {
    return 'tel:+' + phone;
  }

  private runAnalytics() {
    this.$mixpanel.track('Profile Enquire');
    this.$gtag.event('Profile Enquire');
    fbq('track', 'Profile Enquire');
  }

  private runAnalyticsTab(evnetName: string) {
    this.$mixpanel.track(evnetName);
    this.$gtag.event(evnetName);
    fbq('track', evnetName);
  }

  private runAnalyticsViewProfile() {
    this.$mixpanel.track('Customer Viewed Mechanic Job Portfolio');
    this.$gtag.event('Customer Viewed Mechanic Job Portfolio');
    fbq('track', 'Customer Viewed Mechanic Job Portfolio');
  }
}
</script>

<style scoped>
  .bottom-right {
    bottom: 1.5rem;
    right: 1.5rem;
  }
</style>
