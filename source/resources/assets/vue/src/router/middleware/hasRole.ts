import { NavigationGuardNext, Route } from 'vue-router';
import store from '@/store';

export const ROLE_DRIVER = 'driver';
export const ROLE_MECHANIC = 'mechanic';

export default async function (to: Route, _from: Route, next: NavigationGuardNext) {
  if (to.meta.role === undefined) next();

  if (store.getters['auth/authenticated']
    && to.meta.role !== store.getters['auth/user'].role) next(false);
  else next();
}
