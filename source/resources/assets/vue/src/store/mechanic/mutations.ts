import { MechanicState } from './state';
import * as mutationTypes from './mutationTypes';

export default {
  [mutationTypes.SET_MECHANIC_PROFILE](
    state: MechanicState, mechanicProfile: Record<string, number|string|object>,
  ): void {
    state.mechanicProfile = mechanicProfile;
  },
  [mutationTypes.SET_MECHANIC_SERVICES](
    state: MechanicState, services: Array<Record<string, number|string|object>>,
  ): void {
    state.services = services;
  },
  [mutationTypes.SET_MECHANIC_SERVICES_META](
    state: MechanicState, meta: Record<string, number|string|object>,
  ): void {
    state.servicesMeta = meta;
  },
  [mutationTypes.CLEAR_MECHANIC_SERVICES](state: MechanicState): void {
    state.services = [];
  },
  [mutationTypes.CLEAR_MECHANIC_SERVICES_META](state: MechanicState): void {
    state.servicesMeta = {};
  },
  [mutationTypes.SET_MECHANIC_FULFILLED_JOBS](
    state: MechanicState, fulfilledJobs: Array<Record<string, number|string|object>>,
  ): void {
    state.fulfilledJobs = fulfilledJobs;
  },
  [mutationTypes.SET_MECHANIC_FULFILLED_JOBS_META](
    state: MechanicState, meta: Record<string, number|string|object>,
  ): void {
    state.fulfilledJobsMeta = meta;
  },
};
