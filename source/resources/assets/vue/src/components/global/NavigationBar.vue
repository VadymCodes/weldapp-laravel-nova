<template>
  <div>
    <nav class="px-6 py-2 flex flex-row justify-between items-center border-gray-300 border-b-2">
      <router-link :to="{ name: 'home' }">
        <LogoAlpha v-if="authenticated"/>
        <LogoWithTextAlpha v-else />
      </router-link>
      <ul class="flex align-middle">
        <template v-if="!authenticated">
          <li>
            <router-link :to="{ name: 'register' }">Register</router-link>
          </li>
          <li class="ml-4">
            <router-link :to="{ name: 'auth.login' }">Login</router-link>
          </li>
        </template>
        <template v-else>
          <li>
            <button class="m font-bold text-royal-blue" @click.prevent="logOut">Log out</button>
          </li>
        </template>
      </ul>
    </nav>
    <VerifyEmailBanner v-if="authenticated && !user.email_verified_at" />
    <VerifyPhoneBanner v-if="authenticated && !user.phone_verified" />
    <VerifyIDBanner v-if="authenticated && user.role=='mechanic' && !user.verified && !user.uploaded_doc" />
  </div>
</template>

<script lang="ts">
import { mapGetters, mapActions } from 'vuex';
import { Component, Vue } from 'vue-property-decorator';

import Logo from '@/components/global/Logo.vue';
import LogoWithText from '@/components/global/LogoWithText.vue';
import LogoAlpha from '@/components/global/LogoAlpha.vue';
import LogoWithTextAlpha from '@/components/global/LogoWithTextAlpha.vue';
import VerifyEmailBanner from '@/components/global/VerifyEmailBanner.vue';
import VerifyPhoneBanner from '@/components/global/VerifyPhoneBanner.vue';
import VerifyIDBanner from '@/components/global/VerifyIDBanner.vue';

@Component({
  components: {
    Logo,
    LogoWithText,
    LogoAlpha,
    LogoWithTextAlpha,
    VerifyEmailBanner,
    VerifyPhoneBanner,
    VerifyIDBanner,
  },
  computed: {
    ...mapGetters({
      authenticated: 'auth/authenticated',
      user: 'auth/user',
    }),
  },
  methods: {
    ...mapActions({
      logout: 'auth/logout',
    }),
  },
})
export default class NavigationBar extends Vue {
  public async logOut() {
    // eslint-disable-next-line @typescript-eslint/no-explicit-any
    await (this as any).logout();

    this.$router.replace({ name: 'auth.login' });
  }
}
</script>
