<template>
  <main>
    <NavigationBar />
    <template v-if="Object.keys(job).length">
      <div class="flex flex-col py-8 px-6 bg-gray-100">
        <h1 class="text-2xl font-bold text-gray-900">Test Job</h1>
        <!-- eslint-disable-next-line  -->
        <p class="mt-4 text-gray-700">Posted by <span class="font-medium">{{ job.user.name }}</span> in <span class="font-medium">{{ job.category.name }}</span></p>
        <!-- eslint-disable-next-line  -->
        <p class="mt-2 text-gray-700">
          {{ getBider(job) }}
        </p>
      </div>
      <!-- eslint-disable-next-line  -->
      <p class="mt-8 px-6 text-gray-700">Working on a <span class="font-medium text-royal-blue">{{ job.vehicle }}</span></p>
      <!-- eslint-disable-next-line  -->
      <p class="mt-6 mb-8 px-6 text-gray-900 leading-relaxed whitespace-pre-line">{{ job.description }}</p>
    </template>
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
      user: 'auth/user',
      fulfilledJob: 'driver/fulfilledJob',
      job: 'driver/job',
    }),
  },
  methods: {
    ...mapActions({
      getFulfilledJob: 'driver/getFulfilledJob',
      getJob: 'driver/getJob',
    }),
    // eslint-disable-next-line @typescript-eslint/no-explicit-any
    getBider(job: any) {
      if (job.bider) {
        return `Job undertaken by ${job.bider.name}`;
      }
      return job.bids.length > 0 ? `${job.bids.length} bids` : 'No bids yet';
    },
  },
})
export default class ActiveJob extends Vue {
  async mounted() {
    // eslint-disable-next-line @typescript-eslint/no-explicit-any
    await (this as any).getJob(this.$route.params.id);
  }
}
</script>
