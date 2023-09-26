<template>
  <div v-if="loaded">
    <div v-for="item in items" :key="item.id">
      <slot name="item" v-bind:item="item" />
    </div>
    <div v-if="items.length" v-observe-visibility="handleScrolledToBottom"></div>
  </div>
  <loading v-else/>
</template>

<script lang="ts">
import { Component, Vue } from 'vue-property-decorator';
import { mapGetters } from 'vuex';
import Loading from './Loading.vue';

@Component({
  components: {
    Loading,
  },
  props: {
    items: {
      type: Array,
      required: true,
    },
    lastPage: {
      type: Number,
      required: true,
    },
  },
  computed: {
    ...mapGetters({
      loaded: 'loaded',
    }),
  },
})
export default class InfiniteScroll extends Vue {
  private currentPage = 1;

  private handleScrolledToBottom(visible: boolean) {
    if (!visible) {
      return;
    }

    if (this.currentPage >= this.$props.lastPage) {
      return;
    }

    this.currentPage += 1;

    this.$emit('inifinite-scroll:fetch', this.currentPage);
  }
}
</script>
