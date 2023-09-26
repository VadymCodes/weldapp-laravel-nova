<template>
  <div v-if="authenticated" class="fixed bottom-0 mb-10 w-full z-0">
    <!-- eslint-disable-next-line -->
    <div class="w-32 text-center bg-royal-blue rounded-full py-3 px-8 text-2xl shadow-custom mx-auto">
      <font-awesome-icon icon="home" class="mr-2 text-white cursor-pointer"
        @click.prevent="goHome()"/>
      <font-awesome-icon icon="user" class="text-white cursor-pointer"
        @click.prevent="goProfile()"/>
    </div>
  </div>
</template>

<script lang="ts">
import { Component, Vue } from 'vue-property-decorator';
import { mapGetters } from 'vuex';

@Component({
  computed: {
    ...mapGetters({
      user: 'auth/user',
      authenticated: 'auth/authenticated',
    }),
  },
})

export default class GlobalNav extends Vue {
  private goHome() {
    this.$router.replace({ name: 'home' });
  }

  private goProfile() {
    const user = this.$store.getters['auth/user'];
    if (user.role === 'driver') {
      this.$router.push(`/drivers/${user.id}/profile`);
    } else {
      this.$router.push(`/mechanics/${user.id}/profile`);
    }
  }
}
</script>
