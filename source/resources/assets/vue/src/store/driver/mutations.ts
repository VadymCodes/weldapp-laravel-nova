import { DriverState } from './state';
import * as mutationTypes from './mutationTypes';

export default {
  [mutationTypes.SET_DRIVER_PROFILE](
    state: DriverState, driverProfile: Record<string, number|string|object>,
  ): void {
    state.driverProfile = driverProfile;
  },
  [mutationTypes.SET_JOB](
    state: DriverState, job: Record<string, number|string|object>,
  ): void {
    state.job = job;
  },
  [mutationTypes.SET_FULFILLED_JOB](
    state: DriverState, fulfilledJob: Record<string, number|string|object>,
  ): void {
    state.fulfilledJob = fulfilledJob;
  },
  [mutationTypes.SET_JOB_BIDS](
    state: DriverState, bids: Array<Record<string, number|string|object>>,
  ): void {
    state.bids = bids;
  },
  [mutationTypes.SET_JOB_BIDS_META](
    state: DriverState, bidsMeta: Record<string, number|string|object>,
  ): void {
    state.bidsMeta = bidsMeta;
  },
  [mutationTypes.SET_JOB_BID](
    state: DriverState, bid: Record<string, number|string|object>,
  ): void {
    state.bids.unshift(bid);
  },
};
