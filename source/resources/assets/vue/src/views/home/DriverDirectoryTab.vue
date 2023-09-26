<template>
  <ais-instant-search :search-client="searchClient"
    :index-name="index"
    >
    <ais-configure :hits-per-page.camel="5"
      :aroundLatLng="location"
      :aroundRadius="radius"
      v-bind="{
        filters: 'role:mechanic'
      }"/>
    <InfiniteHits>
      <template slot="item" slot-scope="{ item }">
        <div class="mx-6 mb-8 rounded-md border-gray-300 border-2">
          <div class="flex p-4 bg-gray-100 rounded-tl-md rounded-tr-md">
            <router-link :to="{ name: 'mechanic.profile', params: { id: item.id } }"
              class="text-xl font-bold text-gray-900"
              v-text="item.name">
            </router-link>
          </div>
          <star-rating
            v-if="!isShowRating"
            :read-only="true"
            :rounded-corners="true"
            :star-size="15"
            class="m-2"
            :rating="0"/>
          <star-rating
            v-if="showRating(item.id) && isShowRating"
            :read-only="true"
            :rounded-corners="true"
            :star-size="15"
            :increment="0.1"
            class="m-2"
            :rating="ratings[item.id]"/>
          <p class="mt-4 px-4 font-medium text-gray-700">{{ item.address }}</p>
          <!-- eslint-disable-next-line  -->
          <a class="w-full block mt-6 p-4 text-white text-center font-bold bg-royal-blue rounded-bl-md rounded-br-md"
            :href="`tel:${item.phone}`" @click="runAnalytics">
            Enquire
          </a>
        </div>
      </template>
    </InfiniteHits>
  </ais-instant-search>
</template>

<script lang="ts">
import { Component, Vue } from 'vue-property-decorator';
import StarRating from 'vue-star-rating';
import { mapGetters, mapActions } from 'vuex';

import InfiniteHits from '@/components/global/InfiniteHits.vue';

@Component({
  components: {
    InfiniteHits,
    StarRating,
  },
  props: {
    searchClient: {
      type: Object,
      required: true,
    },
    index: {
      type: String,
      required: true,
    },
    location: {
      type: String,
      required: true,
    },
    radius: {
      type: Number,
      required: true,
    },
  },
  computed: {
    ...mapGetters({
      reviewedJobs: 'mechanic/fulfilledJobs',
    }),
  },
  methods: {
    ...mapActions({
      getMechanicsFulfilledJobs: 'mechanic/getMechanicFulfilledJobsForRating',
    }),
  },
})
export default class DriverDirectoryTab extends Vue {

  private ratings: any = {};

  private isShowRating = false;

  private showRating(id: number) {
    // eslint-disable-next-line @typescript-eslint/no-explicit-any
    (this as any).getMechanicsFulfilledJobs({ id: id }).then((res: any) => {
      this.ratings[id] = (this as any).getAvgRating((this as any).reviewedJobs);

      setTimeout(() => {
        this.isShowRating = true;
      }, 3000)
    });

    return true;
  }

  // eslint-disable-next-line @typescript-eslint/no-explicit-any
  private getAvgRating(jobs: any[]) {
    let rating = 0;
    const reviews = jobs.filter((job) => job.mechanic_rating != null);
    if (reviews.length > 0) {
      reviews.forEach((job) => {
        rating += job.mechanic_rating;
      });
      return rating / reviews.length;
    }

    return 0;
  }

  private runAnalytics() {
    this.$mixpanel.track('Home Enquire');
    this.$gtag.event('Home Enquire');
    fbq('track', 'Home Enquire');
  }
}
</script>
