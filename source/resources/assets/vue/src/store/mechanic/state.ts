export type MechanicState = {
  mechanicProfile: Record<string, number|string|object>;
  services: Array<Record<string, number|string|object>>;
  servicesMeta: Record<string, number|string|object>;
  fulfilledJobs: Array<Record<string, number|string|object>>;
  fulfilledJobsMeta: Record<string, number|string|object>;
}

const state: MechanicState = {
  mechanicProfile: {},
  services: [],
  servicesMeta: {},
  fulfilledJobs: [],
  fulfilledJobsMeta: {},
};

export default state;
