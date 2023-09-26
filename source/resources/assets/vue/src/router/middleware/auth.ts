import { NavigationGuardNext, Route } from 'vue-router';
import store from '@/store';

export default async function (to: Route, _from: Route, next: NavigationGuardNext) {
  if (to.meta.requiresAuth === undefined) next();

  if (!to.meta.requiresAuth && store.getters['auth/authenticated']) next({ name: 'home' });
  else next();
}
