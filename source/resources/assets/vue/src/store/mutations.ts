import * as mutationTypes from './mutationTypes';
import { GlobalState } from './state';

export default {
  [mutationTypes.SET_VALIDATION_ERRORS](state: GlobalState, errors: []): void {
    state.validationErrors = errors;
  },
  [mutationTypes.CLEAR_VALIDATION_ERRORS](state: GlobalState): void {
    state.validationErrors = [];
  },
  [mutationTypes.SET_ERROR](state: GlobalState, error: string): void {
    state.error = error;
  },
  [mutationTypes.CLEAR_ERROR](state: GlobalState): void {
    state.error = '';
  },
  [mutationTypes.SET_LOADED](state: GlobalState): void {
    state.loaded = true;
  },
  [mutationTypes.CLEAR_LOADED](state: GlobalState): void {
    state.loaded = false;
  },
};
