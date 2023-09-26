import { DriverState } from './state';

export const driverProfile = (
  state: DriverState,
): Record<string, number|string|object> => state.driverProfile;

export const job = (
  state: DriverState,
): Record<string, number|string|object> => state.job;

export const fulfilledJob = (
  state: DriverState,
): Record<string, number|string|object> => state.fulfilledJob;

export const jobBids = (
  state: DriverState,
): Array<Record<string, number|string|object>> => state.bids;

export const jobBidsMeta = (
  state: DriverState,
): Record<string, number|string|object> => state.bidsMeta;
