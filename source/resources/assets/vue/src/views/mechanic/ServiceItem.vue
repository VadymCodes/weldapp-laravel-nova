<template>
    <div class="mx-6 mb-8 rounded-md border-gray-300 border-2">
      <div class="flex flex-col p-4 bg-gray-100 rounded-tl-md rounded-tr-md">
        <h2 class="text-xl font-bold text-gray-900">{{ name }}</h2>
        <p class="mt-2 text-gray-700 font-medium" v-if="mechanic.name">{{ mechanic.name }}</p>
      </div>
      <p class="mt-6 px-4 font-bold text-gray-900">{{ mechanic.address.address }}</p>
      <!-- eslint-disable-next-line  -->
      <p class="mt-4 px-4 text-gray-900"><em class="text-royal-blue">Cost</em>: {{ cost }}</p>
      <!-- eslint-disable-next-line  -->
      <p class="mt-4 px-4 text-gray-900 leading-relaxed whitespace-pre-line">{{ description }}</p>
      <!-- eslint-disable-next-line  -->
      <a class="w-full block mt-6 p-4 text-white text-center font-bold bg-royal-blue rounded-bl-md rounded-br-md"
        :href="`tel:${mechanic.phone}`">
        Enquire
      </a>
    </div>
</template>

<script lang="ts">
import { Component, Vue } from 'vue-property-decorator';

@Component({
  props: {
    name: {
      type: String,
      required: true,
    },
    cost: {
      type: String,
      required: true,
    },
    description: {
      type: String,
      required: true,
    },
    mechanic: {
      type: Object,
      required: true,
    },
  },
})
export default class ServiceItem extends Vue {}
</script>
