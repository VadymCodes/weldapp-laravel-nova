export type JobState = {
  activeJobs: Array<Record<string, number|string|object>>;
  fulfilledJobs: Array<Record<string, number|string|object>>;
  unfulfilledJobs: Array<Record<string, number|string|object>>;
  activeJobsMeta: Record<string, number|string|object>;
  fulfilledJobsMeta: Record<string, number|string|object>;
  unfulfilledJobsMeta: Record<string, number|string|object>;
};

const state: JobState = {
  activeJobs: [],
  fulfilledJobs: [],
  unfulfilledJobs: [],
  activeJobsMeta: {},
  fulfilledJobsMeta: {},
  unfulfilledJobsMeta: {},
};

export default state;
