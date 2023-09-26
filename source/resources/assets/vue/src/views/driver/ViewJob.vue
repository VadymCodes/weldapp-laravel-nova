<template>
  <main>
    <NavigationBar />
    <template v-if="Object.keys(job).length && job.id == this.$route.params.id">
      <div class="flex flex-col py-8 px-6 bg-gray-100">
        <h1 class="text-2xl font-bold text-gray-900">{{ job.name }}</h1>
        <!-- eslint-disable-next-line  -->
        <p class="mt-2 text-gray-700">Posted by <span class="font-medium">{{ job.user.name }}</span> in <span class="font-medium">{{ job.category.name }}</span></p>
      </div>
      <p class="mt-4 px-6 font-medium text-gray-700">Vehicle: {{ job.vehicle }}</p>
      <!-- eslint-disable-next-line  -->
      <p class="mt-4 mb-4 px-6 text-gray-900 leading-relaxed whitespace-pre-line">{{ job.description }}</p>
      <hr class="mt-4 mb-8 border border-gray-300 bg-gray-300" />
      <h2 class="mb-4 px-6 text-xl font-bold text-gray-900">Bids</h2>
      <p v-show="bids.length === 0"
        class="mt-48 px-6 text-center text-gray-700 text-xl">No bids yet!</p>
      <InifiniteScroll v-if="bidsMeta.last_page"
        class="mb-24"
        @inifinite-scroll:fetch="getBids"
        :items="bids"
        :lastPage="bidsMeta.last_page">
        <template v-slot:item="{ item }">
          <div class="mx-6 mb-8 rounded-md border-gray-300 border-2">
            <div class="flex flex-col p-4 bg-gray-100 rounded-tl-md rounded-tr-md">
              <router-link class="text-xl font-bold text-gray-900"
                :to="{ name: 'mechanic.profile', params: { id: item.user.id } }"
                v-text="item.user.name" />
            </div>
            <div class="px-4">
              <!-- eslint-disable-next-line  -->
              <p class="mt-4 mb-4 text-gray-900 font-bold">Bid amount: <span class=" text-royal-blue">{{ item.cost }}</span></p>
              <!-- eslint-disable-next-line  -->
              <p class="mt-4 mb-4 text-gray-900 leading-relaxed whitespace-pre-line">{{ item.comment }}</p>
              <!-- eslint-disable-next-line  -->
              <button class="w-full h-10 mb-4 bg-royal-blue text-white font-bold rounded-md disabled:opacity-50 disabled:pointer-events-none"
                v-if="user.role === 'driver' && !item.fulfilledJob"
                @click="acceptBid(job.id, item.id)"
                :disabled="isSubmitting || job.bider" 
              >
                Accept bid
              </button>
              <button class="w-full h-10 mb-4 bg-royal-blue text-white font-bold rounded-md disabled:opacity-50 disabled:pointer-events-none"
                :disabled="true" 
                v-if="item.fulfilledJob"
              >
                Accepted
              </button>
            </div>
          </div>
        </template>
      </InifiniteScroll>
    </template>
    <div v-show="user.role === 'mechanic'"
      class="fixed bottom-0 right-0 left-0 flex flex-col pt-4 pb-6 px-6 bg-gray-100
        bg-opacity-0 z-10"
      :style="showBidPanel ? 'bottom: 0;' : 'bottom: -230px;'">
      <div class="flex flex-row justify-between items-center">
        <h2 class="text-xl font-bold text-gray-900">Make a bid</h2>
        <p class="text-sm text-royal-blue font-medium"
          @click.prevent="showBidPanel = !showBidPanel">
          <template v-if="showBidPanel">Hide</template>
          <template v-else>Show</template>
        </p>
      </div>
      <form @submit.prevent="bid" class="bg-white">
        <ResizingTextarea class="mb-2 mt-4"
          v-model="form.comment"
          label="Comment"
          placeholder="Labour will cost..." />
        <span class="flex flex-row items-end">
          <label class="block mr-4 w-32">
            <span class="text-gray-700">Cost</span>
            <!-- eslint-disable-next-line max-len -->
            <span class="flex flex-row items-center mt-1 bg-white rounded-md border-gray-300 border">
              <p class="text-gray-900 font-bold pl-2">£</p>
              <input type="number"
                inputmode="numeric" pattern="[0-9]*"
                v-model="form.cost"
                min="0.00"
                max="1000000.00"
                step="0.01"
                class="form-input block w-full border-0 rounded-tl-none rounded-bl-none pl-1"
                placeholder="150.55">
              </span>
          </label>
          <!-- eslint-disable-next-line  -->
          <button class="h-10 flex-1 bg-royal-blue text-white font-bold rounded-md disabled:opacity-50 disabled:pointer-events-none" :disabled="isSubmitting">
            Bid
          </button>
        </span>
      </form>
    </div>
  </main>
</template>

<script lang="ts">
import axios from 'axios';
import { mapGetters, mapActions } from 'vuex';
import { Component, Vue } from 'vue-property-decorator';

import NavigationBar from '@/components/global/NavigationBar.vue';
import InifiniteScroll from '@/components/global/InfiniteScroll.vue';
import ResizingTextarea from '@/components/global/ResizingTextarea.vue';

@Component({
  components: {
    NavigationBar,
    InifiniteScroll,
    ResizingTextarea,
  },
  computed: {
    ...mapGetters({
      user: 'auth/user',
      job: 'driver/job',
      bids: 'driver/jobBids',
      bidsMeta: 'driver/jobBidsMeta',
    }),
  },
  methods: {
    ...mapActions({
      getJob: 'driver/getJob',
      makeBid: 'driver/makeBid',
      getJobBids: 'driver/getJobBids',
      clearJobBids: 'driver/clearJobBids',
      setLoaded: 'setLoaded',
    }),
  },
})
export default class ViewJob extends Vue {
  private isSubmitting = false;

  private showBidPanel = false;

  private form: Record<string, string> = {
    comment: '',
    cost: '',
  };

  async mounted() {
    // eslint-disable-next-line @typescript-eslint/no-explicit-any
    await (this as any).getJob(this.$route.params.id);
    // eslint-disable-next-line @typescript-eslint/no-explicit-any
    await (this as any).getJobBids({ id: this.$route.params.id });
    // eslint-disable-next-line @typescript-eslint/no-explicit-any
    (this as any).setLoaded();
  }

  beforeDestroy() {
    // eslint-disable-next-line @typescript-eslint/no-explicit-any
    (this as any).clearJobBids();
  }

  public setIsSubmitting(isSubmitting: boolean): void {
    this.isSubmitting = isSubmitting;
  }

  public setShowBidPanel(showBidPanel: boolean): void {
    this.showBidPanel = showBidPanel;
  }

  private async getBids(nextPage: number) {
    // eslint-disable-next-line @typescript-eslint/no-explicit-any
    await (this as any).getJobBids({
      id: this.$route.params.id,
      page: nextPage,
    });
  }

  private async bid() {
    this.setIsSubmitting(true);

    // eslint-disable-next-line @typescript-eslint/no-explicit-any
    await (this as any).makeBid({
      id: this.$route.params.id,
      bid: this.form,
    });

    this.setIsSubmitting(false);
    this.setShowBidPanel(false);
  }

  // eslint-disable-next-line class-methods-use-this
  private acceptBid(job: number, bid: number) {
    this.setIsSubmitting(true);

    // eslint-disable-next-line @typescript-eslint/camelcase
    axios.post(`api/v1/drivers/jobs/${job}/accept`, { bid_id: bid })
      .then(({ data }) => {
        this.$router.replace({
          name: 'driver.job.active.show',
          params: { id: data.id },
        });
      }).catch(() => this.setIsSubmitting(false));
  }
}
</script>
