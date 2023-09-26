<template>
  <div class="absolute w-full h-full bg-white text-center table inset-0 z-50">
    <div class="table-cell align-middle">
      <div class="mx-auto">
        <LogoWithTextAlpha class="h-8 mx-auto" />
        <loading-progress
          :progress="100"
          :indeterminate="true"
          :counter-clockwise="false"
          :hide-background="false"
          size="100"
          height="30"
          shape="line"
          fillDuration="1"
        />
      </div>
    </div>
  </div>
</template>

<script>
// import LogoWithText from './LogoWithText.vue';
import LogoWithTextAlpha from './LogoWithTextAlpha.vue';

export default {
  name: 'LoadingOverlay',
  components: {
    // LogoWithText,
    LogoWithTextAlpha,
  },
};
</script>

<style type="text/css">
  .vue-progress-path path {
    stroke-width: 3;
  }
  .vue-progress-path .progress {
    stroke: #0078B6;
  }
</style>
