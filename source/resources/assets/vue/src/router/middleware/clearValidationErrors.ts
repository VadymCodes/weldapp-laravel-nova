import { NavigationGuardNext, Route } from 'vue-router';
import store from '@/store';

export default function (_to: Route, _from: Route, next: NavigationGuardNext) {
  store.dispatch('clearValidationErrors');

  next();
}
