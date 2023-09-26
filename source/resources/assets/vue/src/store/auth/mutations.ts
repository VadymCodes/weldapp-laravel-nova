// import localforage from 'localforage';

import { AuthState } from './state';
import * as mutationTypes from './mutationTypes';

export default {
  [mutationTypes.SET_AUTHENTICATED](state: AuthState, authenticated: boolean): void {
    state.authenticated = authenticated;
  },
  [mutationTypes.SET_USER](state: AuthState, user: Record<string, string>): void {
    state.user = user;
  },
  [mutationTypes.SET_REG_ROLE](state: AuthState, regRole: string): void {
    state.regRole = regRole;
  },
};
