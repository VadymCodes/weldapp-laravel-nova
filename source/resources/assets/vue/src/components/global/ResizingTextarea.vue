<template>
  <label class="block">
    <span v-if="label" class="text-gray-700" :class="labelClass">{{ label }}</span>
    <!-- eslint-disable-next-line  -->
    <textarea class="form-textarea mt-1 block w-full box-border overflow-hidden"
      style="min-height: 100px; max-height: 250px;"
      :class="textareaClass"
      :placeholder="placeholder"
      :value="value"
      @input="$emit('input', $event.target.value); resize($event)"></textarea>
  </label>
</template>

<script lang="ts">
import { Component, Vue } from 'vue-property-decorator';

@Component({
  props: {
    label: {
      type: String,
      required: false,
    },
    labelClass: {
      type: String,
      required: false,
    },
    placeholder: {
      type: String,
      required: false,
    },
    textareaClass: {
      type: String,
      required: false,
    },
    value: {
      type: String,
      required: false,
    },
  },
})
export default class ResizingTextarea extends Vue {
  // eslint-disable-next-line
  public resize(event: any): void {
    const e = event;

    e.target.style.height = 'auto';
    e.target.style.height = `${e.target.scrollHeight}px`;
  }
}
</script>
