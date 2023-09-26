<template>
  <ais-instant-search :search-client="searchClient"
    :index-name="index">
    <ais-configure :hits-per-page.camel="5"
      :aroundLatLng="location"
      :aroundRadius="radius" />
    <InfiniteHits>
      <template slot="item" slot-scope="{ item }">
        <ServiceItem :name="item.name"
          :cost="item.cost"
          :description="item.description"
          :mechanic="item.mechanic" />
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
export default class DriverServicesTab extends Vue {}
</script>
