import * as mutationTypes from './mutationTypes';
import { JobState } from './state';

export default {
  [mutationTypes.SET_ACTIVE_JOBS](
    state: JobState,
    jobs: Array<Record<string, number|string|object>>,
  ): void {
    state.activeJobs = jobs;
  },
  [mutationTypes.SET_ACTIVE_JOBS_META](
    state: JobState,
    meta: Record<string, number|string|object>,
  ): void {
    state.activeJobsMeta = meta;
  },
  [mutationTypes.SET_FULFILLED_JOBS](
    state: JobState,
    jobs: Array<Record<string, number|string|object>>,
  ): void {
    state.fulfilledJobs = jobs;
  },
  [mutationTypes.SET_FULFILLED_JOBS_META](
    state: JobState,
    meta: Record<string, number|string|object>,
  ): void {
    state.fulfilledJobsMeta = meta;
  },
  [mutationTypes.SET_UNFULFILLED_JOBS](
    state: JobState,
    jobs: Array<Record<string, number|string|object>>,
  ): void {
    state.unfulfilledJobs = jobs;
  },
  [mutationTypes.SET_UNFULFILLED_JOBS_META](
    state: JobState,
    meta: Record<string, number|string|object>,
  ): void {
    state.unfulfilledJobsMeta = meta;
  },
};
