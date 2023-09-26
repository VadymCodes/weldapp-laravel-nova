<template>
  <div>
    <div v-if="loaded && state && state.hits.length > 0">
      <ais-stats class="px-6 pb-4 text-gray-600 text-sm" />
      <ol>
        <li v-for="hit in state.hits" :key="hit.objectID">
          <slot name="item" :item="hit"> </slot>
        </li>
        <li class="sentinel" v-observe-visibility="visibilityChanged" />
      </ol>
    </div>
    <p v-else-if="loaded"
      class="mt-64 mx-6 text-center text-gray-700 text-xl">
      No results have been found.
    </p>
    <loading v-if="!loaded"/>
  </div>
</template>

<script>
import { createWidgetMixin } from 'vue-instantsearch';
import { connectInfiniteHits } from 'instantsearch.js/es/connectors';

import Loading from './Loading.vue';

export default {
  components: {
    Loading,
  },
  mixins: [createWidgetMixin({ connector: connectInfiniteHits })],
  methods: {
    visibilityChanged(isVisible) {
      if (isVisible && !this.state.isLastPage) {
        this.state.showMore();
      }
    },
  },
  computed: {
    loaded() {
      return this.$store.getters.loaded;
    },
  },
  watch: {
    state(val) {
      if (val) {
        this.$store.dispatch('setLoaded');
      }
    },
  },
};
</script>
