import { Commit } from 'vuex';
import axios from 'axios';
import * as mutationTypes from './mutationTypes';

export const getDriverProfile = async ({ commit }: { commit: Commit }, id: number) => {
  const { data } = await axios.get(`api/v1/drivers/${id}/profile`);

  commit(mutationTypes.SET_DRIVER_PROFILE, data.data);
};

export const getJob = async ({ commit }: { commit: Commit }, id: number) => {
  const { data } = await axios.get(`api/v1/drivers/jobs/${id}`);

  commit(mutationTypes.SET_JOB, data.data);
};

export const getFulfilledJob = async ({ commit }: { commit: Commit }, id: number) => {
  const { data } = await axios.get(`api/v1/drivers/jobs/active/${id}`);

  commit(mutationTypes.SET_FULFILLED_JOB, data.data);
};

export const getJobBids = async (
  { commit }: { commit: Commit }, { id, page = 1 }: { id: number; page: number },
) => {
  const { data } = await axios.get(`api/v1/drivers/jobs/bids/${id}?page=${page}`);

  commit(mutationTypes.SET_JOB_BIDS, data.data);
  commit(mutationTypes.SET_JOB_BIDS_META, data.meta);
};

export const clearJobBids = async ({ commit }: { commit: Commit }) => {
  commit(mutationTypes.SET_JOB_BIDS, []);
  commit(mutationTypes.SET_JOB_BIDS_META, {});
};

export const makeBid = async (
  { commit }: { commit: Commit }, { id, bid }: { id: number; bid: object },
) => {
  const { data } = await axios.post(`api/v1/drivers/jobs/bids/${id}`, bid);

  commit(mutationTypes.SET_JOB_BID, data.data);
};
