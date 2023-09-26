<template>
  <ais-instant-search :search-client="searchClient"
    :index-name="index">
    <ais-configure :hits-per-page.camel="5"
      :aroundLatLng="location"
      :aroundRadius="radius" />
    <div class="flex flex-row items-end mb-6 pb-4 px-6 bg-gray-100">
      <label class="block flex-1">
        <span class="text-gray-700">Select a Category</span>
        <ais-menu-select attribute="category">
          <select class="form-select mt-1 block w-full"
            slot-scope="{ items, canRefine, refine }"
            :disabled="!canRefine"
            @change="refine($event.currentTarget.value)">
            <option value="">All</option>
            <option v-for="item in items"
              :key="item.value"
              :value="item.value"
              :selected="item.isRefined">
              {{ item.label }}
            </option>
          </select>
        </ais-menu-select>
      </label>
    </div>
    <InfiniteHits>
      <template slot="item" slot-scope="{ item }">
        <div class="mx-6 mb-8 rounded-md border-gray-300 border-2">
          <div class="flex flex-col p-4 bg-gray-100 rounded-tl-md rounded-tr-md">
            <router-link class="text-xl font-bold text-gray-900"
              :to="{ name: 'driver.job.show', params: { id: item.id } }"
              v-text="item.name" />
            <!-- eslint-disable-next-line  -->
            <p class="mt-2 text-gray-700">Posted by <span class="font-medium">{{ item.driver.name }}</span> in <span class="font-medium">{{ item.category }}</span></p>
          </div>
          <p class="mt-4 px-4 font-medium text-gray-700">Vehicle: {{ item.vehicle }}</p>
          <!-- eslint-disable-next-line  -->
          <p class="mt-4 mb-4 px-4 text-gray-900 leading-relaxed whitespace-pre-line">{{ item.description }}</p>
        </div>
      </template>
    </InfiniteHits>
  </ais-instant-search>
</template>

<script lang="ts">
import { Component, Vue } from 'vue-property-decorator';

import ServiceItem from '@/views/mechanic/ServiceItem.vue';
import InfiniteHits from '@/components/global/InfiniteHits.vue';

@Component({
  components: {
    ServiceItem,
    InfiniteHits,
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
})
export default class MechanicJobsTab extends Vue {}
</script>
