import { Commit } from 'vuex';
import axios from 'axios';
import * as mutationTypes from './mutationTypes';

export const getMechanicProfile = async ({ commit }: { commit: Commit }, id: number) => {
  const { data } = await axios.get(`api/v1/mechanics/${id}/profile`);

  commit(mutationTypes.SET_MECHANIC_PROFILE, data.data);
};

export const getMechanicServices = async (
  { commit }: { commit: Commit }, { id, page = 1 }: { id: number; page: number },
) => {
  const { data } = await axios.get(`api/v1/mechanics/${id}/services?page=${page}`);

  commit(mutationTypes.SET_MECHANIC_SERVICES, data.data);
  commit(mutationTypes.SET_MECHANIC_SERVICES_META, data.meta);
};

export const clearMechanicServices = async ({ commit }: { commit: Commit }) => {
  commit(mutationTypes.CLEAR_MECHANIC_SERVICES);
  commit(mutationTypes.CLEAR_MECHANIC_SERVICES_META);
};

export const getMechanicFulfilledJobs = async (
  { commit }: { commit: Commit }, { id, page = 1 }: { id: number; page: number },
) => {
  const { data } = await axios.get(`api/v1/mechanics/${id}/fulfilled?page=${page}`);

  commit(mutationTypes.SET_MECHANIC_FULFILLED_JOBS, data.data);
  commit(mutationTypes.SET_MECHANIC_FULFILLED_JOBS_META, data.meta);
};

export const getMechanicFulfilledJobsForRating = async (
  { commit }: { commit: Commit }, { id, page = 1 }: { id: number; page: number },
) => {
  const { data } = await axios.get(`api/v1/mechanics/${id}/fulfilled-for-rating`);

  commit(mutationTypes.SET_MECHANIC_FULFILLED_JOBS, data.data);
  commit(mutationTypes.SET_MECHANIC_FULFILLED_JOBS_META, data.meta);
};
