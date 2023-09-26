import { Commit } from 'vuex';
import * as mutationTypes from './mutationTypes';

export const setValidationErrors = ({ commit }: { commit: Commit }, errors: Array<object>) => {
  commit(mutationTypes.SET_VALIDATION_ERRORS, errors);
};

export const clearValidationErrors = ({ commit }: { commit: Commit }) => {
  commit(mutationTypes.CLEAR_VALIDATION_ERRORS, []);
};

export const setError = ({ commit }: { commit: Commit }, errors: Array<object>) => {
  commit(mutationTypes.SET_ERROR, errors);
};

export const clearError = ({ commit }: { commit: Commit }) => {
  commit(mutationTypes.CLEAR_ERROR, []);
};

export const setLoaded = ({ commit }: { commit: Commit }) => {
  commit(mutationTypes.SET_LOADED);
};

export const clearLoaded = ({ commit }: { commit: Commit }) => {
  commit(mutationTypes.CLEAR_LOADED);
};
