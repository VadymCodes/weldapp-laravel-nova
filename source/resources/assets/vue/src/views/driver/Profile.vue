<template>
  <main>
    <NavigationBar />
    <div class="flex py-8 px-6">
      <div class="w-24 h-24 bg-gray-100 rounded-full">
        <img src="@/assets/images/profile.jpeg" class="rounded-full" />
      </div>
      <div class="flex-1 pl-4">
        <h1 class="mb-2 text-xl font-bold text-gray-900"
          v-if="driverProfile">{{ driverProfile.name }}</h1>
        <star-rating
          :read-only="true"
          :rounded-corners="true"
          :increment="0.1"
          :star-size="15"
          class="m-2"
          v-model="avgRating"/>
        <router-link class="text-mg text-royal-blue"
          :to="{ name: 'auth.update-password' }"
        >Change password</router-link>
      </div>
    </div>
    <ul class="flex mx-6 mb-6 p-1 bg-gray-100 rounded-lg">
      <li class="flex-1">
        <!-- eslint-disable-next-line  -->
        <button class="w-full p-2 font-bold text-gray-900"
          :class="{ 'bg-white rounded-md shadow-sm ': isServicesTab  }"
          @click.prevent="switchTab(0)">Active Job</button>
      </li>
      <li class="flex-1">
        <button class="w-full p-2 font-bold text-gray-900"
          :class="{ 'bg-white rounded-md shadow-sm ': isDirectoryTab  }"
          @click.prevent="switchTab(1)">Job History</button>
      </li>
    </ul>
    <template v-if="isServicesTab">
      <p v-show="activeJobs.length === 0"
        class="mt-48 px-6 text-center text-gray-700 text-xl">No active jobs yet!</p>
      <InifiniteScroll v-if="activeJobsMeta.last_page"
        @inifinite-scroll:fetch="getDriversActiveJobs"
        :items="activeJobs"
        :lastPage="activeJobsMeta.last_page">
        <template v-slot:item="{ item }">
          <div class="mx-6 mb-8 rounded-md border-gray-300 border-2">
            <div class="flex flex-col p-4 bg-gray-100 rounded-tl-md rounded-tr-md">
              <router-link class="text-xl font-bold text-gray-900"
              :to="{ name: 'driver.job.show', params: { id: item.id } }"
                v-text="item.name"
              ></router-link>
              <!-- eslint-disable-next-line  -->
              <p class="mt-2 text-gray-700">
                {{ getBider(item) }}
              </p>

            </div>
            <!-- eslint-disable-next-line  -->
            <p class="mt-4 px-4 text-gray-700">
              Fulfillment status:
              <span class="font-medium text-royal-blue">
                <template v-if="item.fulfilled === true">Fulfilled</template>
                <template v-else>Unfulfilled</template>
              </span>
            </p>
            <!-- eslint-disable-next-line  -->
            <p class="mt-4 px-4 text-gray-700">Worked on a <span class="font-medium text-royal-blue">{{ item.vehicle }}</span></p>
            <!-- eslint-disable-next-line  -->
            <p class="mt-4 mb-4 px-4 text-gray-900 leading-relaxed whitespace-pre-line">
              <span v-if="item.bid" class="font-medium">
                {{ item.bid.user.name }} said "{{ item.bid.comment }}"
              </span>
            </p>
          </div>
        </template>
      </InifiniteScroll>
    </template>
    <template v-if="isDirectoryTab">
      <p v-show="fulfilledJobs.length === 0"
        class="mt-48 px-6 text-center text-gray-700 text-xl">No fulfilled jobs yet!</p>
      <InifiniteScroll v-if="fulfilledJobsMeta.last_page"
        @inifinite-scroll:fetch="getDriversFulfilledJobs"
        :items="fulfilledJobs"
        :lastPage="fulfilledJobsMeta.last_page">
        <template v-slot:item="{ item }">
          <div class="mx-6 mb-8 rounded-md border-gray-300 border-2">
            <div class="flex flex-col p-4 bg-gray-100 rounded-tl-md rounded-tr-md">
              <a href="#" class="text-xl font-bold text-gray-900">{{ item.job.name }}</a>
              <!-- eslint-disable-next-line  -->
              <p class="mt-2 text-gray-700">Job done by <span class="font-medium">{{ item.bid && item.bid.user.name }}</span></p>
            </div>
            <!-- eslint-disable-next-line  -->
            <p class="mt-4 px-4 text-gray-700">
              Fulfillment status:
              <span class="font-medium text-royal-blue">
                <template v-if="item.fulfilled === true">Fulfilled</template>
                <template v-else>Unfulfilled</template>
              </span>
            </p>
            <!-- eslint-disable-next-line  -->
            <p class="mt-4 px-4 text-gray-700">Working on a <span class="font-medium text-royal-blue">{{ item.job.vehicle }}</span></p>
            <div class="m-3">
              <div v-if="item.driver_rating">
                <p class="text-gray-700">Mechanic's feedback</p>
                <star-rating
                  :read-only="true"
                  :rounded-corners="true"
                  :animate="true"
                  :star-size="20"
                  class="m-2"
                  v-model="item.driver_rating"/>
                <q>{{ item.driver_comment }}</q>
              </div>
              <p v-else class="text-gray-700">No feedback given</p>
            </div>
            <div class="m-3">
              <div v-if="item.mechanic_rating">
                <p class="text-gray-700">Your feedback</p>
                <star-rating
                  :read-only="true"
                  :rounded-corners="true"
                  :animate="true"
                  :star-size="20"
                  class="m-2"
                  v-model="item.mechanic_rating"/>
                <q>{{ item.mechanic_comment }}</q>
              </div>
              <div v-else>
                <!-- eslint-disable-next-line -->
                <star-rating
                  :star-size="20"
                  :rounded-corners="true"
                  :animate="true"
                  class="m-2"
                  v-model="item.mechanic_rating_new"/>
                <textarea
                  class="form-input w-full" rows="4"
                  v-model="item.mechanic_comment"></textarea>
                <!-- eslint-disable-next-line -->
                <button class="form-input bg-royal-blue text-white rounded-md" @click="sendFeedback(item.job.id, item.mechanic_rating_new, item.mechanic_comment)">Send feedback</button>
              </div>
            </div>
          </div>
        </template>
      </InifiniteScroll>
    </template>
  </main>
</template>

<script lang="ts">
import axios from 'axios';
import { mapGetters, mapActions } from 'vuex';
import { Component, Vue } from 'vue-property-decorator';
import StarRating from 'vue-star-rating';

import NavigationBar from '@/components/global/NavigationBar.vue';
import InifiniteScroll from '@/components/global/InfiniteScroll.vue';

@Component({
  components: {
    NavigationBar,
    InifiniteScroll,
    StarRating,
  },
  computed: {
    ...mapGetters({
      hasError: 'hasError',
      error: 'error',
      user: 'auth/user',
      driverProfile: 'driver/driverProfile',
      activeJobs: 'job/activeJobs',
      fulfilledJobs: 'job/fulfilledJobs',
      unfulfilledJobs: 'job/unfulfilledJobs',
      activeJobsMeta: 'job/activeJobsMeta',
      fulfilledJobsMeta: 'job/fulfilledJobsMeta',
      unfulfilledJobsMeta: 'job/unfulfilledJobsMeta',
    }),
  },
  methods: {
    ...mapActions({
      clearError: 'clearError',
      getDriverProfile: 'driver/getDriverProfile',
      getDriversActiveJobs: 'job/getActiveJobs',
      getDriversFulfilledJobs: 'job/getFulfilledJobs',
      getDriversUnfulfilledJobs: 'job/getUnfulfilledJobs',
      setLoaded: 'setLoaded',
      clearLoaded: 'clearLoaded',
    }),
    // eslint-disable-next-line @typescript-eslint/no-explicit-any
    getBider(item: any) {
      if (item.bider) {
        return `Job undertaken by ${item.bider.name}`;
      }
      return item.bids.length > 0 ? `${item.bids.length} bids` : 'No bids yet';
    },
  },
})
export default class Profile extends Vue {
  private isServicesTab = true;

  private isDirectoryTab = false;

  private avgRating = 0;

  async mounted() {
    // eslint-disable-next-line @typescript-eslint/no-explicit-any
    await (this as any).getDriverProfile(this.$route.params.id);
    // eslint-disable-next-line @typescript-eslint/no-explicit-any
    await (this as any).getDriversActiveJobs({ page: 1 });
    // eslint-disable-next-line @typescript-eslint/no-explicit-any
    await (this as any).getDriversUnfulfilledJobs({ page: 1 });
    // eslint-disable-next-line @typescript-eslint/no-explicit-any
    await (this as any).getDriversFulfilledJobs({ page: 1 });
    // eslint-disable-next-line @typescript-eslint/no-explicit-any
    (this as any).setLoaded();
    // eslint-disable-next-line @typescript-eslint/no-explicit-any
    (this as any).getAvgRating((this as any).fulfilledJobs);
  }

  private async switchTab(tab: number) {
    if (tab === 0) {
      this.isServicesTab = true;
      this.isDirectoryTab = false;

      return;
    }

    this.isServicesTab = false;
    this.isDirectoryTab = true;

    this.runAnalytics();
  }

  private async getActiveJobs(nextPage: number) {
    // eslint-disable-next-line @typescript-eslint/no-explicit-any
    await (this as any).getDriversActiveJobs({ page: nextPage });
  }

  private async getFulfilledJobs(nextPage: number) {
    // eslint-disable-next-line @typescript-eslint/no-explicit-any
    await (this as any).getDriversFulfilledJobs({ page: nextPage });
  }

  private async getUnfulfilledJobs(nextPage: number) {
    // eslint-disable-next-line @typescript-eslint/no-explicit-any
    await (this as any).getDriversUnfulfilledJobs({ page: nextPage });
  }

  // eslint-disable-next-line @typescript-eslint/no-explicit-any
  private getAvgRating(jobs: any[]) {
    let rating = 0;
    if (jobs.length > 0) {
      const reviews = jobs.filter((job) => job.driver_rating != null);
      reviews.forEach((job) => {
        rating += job.driver_rating;
      });
      this.avgRating = rating / reviews.length;
    }
  }

  // eslint-disable-next-line class-methods-use-this
  private sendFeedback(job: number, rating: number, comment: string) {
    axios.post('api/v1/jobs/feedback-by-driver', { job, rating, comment })
      .then(async () => {
        // eslint-disable-next-line @typescript-eslint/no-explicit-any
        await (this as any).getDriversFulfilledJobs({ page: 1 });
      });
  }

  private runAnalytics() {
    if (this.isServicesTab) {
      this.$mixpanel.track('Active Job');
      this.$gtag.event('Active Job');
      fbq('track', 'Active Job');
    } else {
      this.$mixpanel.track('Job History');
      this.$gtag.event('Job History');
      fbq('track', 'Job History');
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
