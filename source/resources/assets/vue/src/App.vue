<template>
  <div id="app" class="pb-20">
    <router-view/>
    <cookie-law theme="base" ref="cookieLaw">
      <div slot="message">
        We use cookies to improve user experience, and analyze website traffic. For these reasons, we may share your site usage data with our analytics partners. By clicking “Got it,” you consent to store on your device all the technologies described in our <a href="/cookie-policy" target="_blank" style="color: royalblue;text-decoration: underline;">Cookie Policy</a>. 
      </div>
    </cookie-law>
    <global-nav/>
  </div>
</template>

<script lang="ts">
import CookieLaw from 'vue-cookie-law'
import { mapGetters } from 'vuex';
import { Component, Vue } from 'vue-property-decorator';
import GlobalNav from './components/global/GlobalNav.vue';

@Component({
  components: {
    CookieLaw,
    GlobalNav,
  },
  computed: {
    ...mapGetters({
      regRole: 'auth/regRole',
    }),
  },
  // watch: {
  //   regRole (newValue, oldValue) {
  //     (this as any).$refs.cookieLaw.revoke();
  //   },
  // },
})

export default class App extends Vue {}
</script>

