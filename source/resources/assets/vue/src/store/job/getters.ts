import { JobState } from './state';

export const activeJobs = (
  state: JobState,
): Array<Record<string, number|string|object>> => state.activeJobs;

export const fulfilledJobs = (
  state: JobState,
): Array<Record<string, number|string|object>> => state.fulfilledJobs;

export const unfulfilledJobs = (
  state: JobState,
): Array<Record<string, number|string|object>> => state.unfulfilledJobs;

export const activeJobsMeta = (
  state: JobState,
): Record<string, number|string|object> => state.activeJobsMeta;

export const fulfilledJobsMeta = (
  state: JobState,
): Record<string, number|string|object> => state.fulfilledJobsMeta;

export const unfulfilledJobsMeta = (
  state: JobState,
): Record<string, number|string|object> => state.unfulfilledJobsMeta;
