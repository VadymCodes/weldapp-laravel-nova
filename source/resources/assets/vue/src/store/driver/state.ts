export type DriverState = {
  driverProfile: Record<string, number|string|object>;
  job: Record<string, number|string|object>;
  fulfilledJob: Record<string, number|string|object>;
  bids: Array<Record<string, number|string|object>>;
  bidsMeta: Record<string, number|string|object>;
}

const state: DriverState = {
  driverProfile: {},
  job: {},
  fulfilledJob: {},
  bids: [],
  bidsMeta: {},
};

export default state;
