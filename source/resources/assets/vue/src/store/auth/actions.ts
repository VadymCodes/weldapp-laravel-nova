import { Commit, Dispatch } from 'vuex';
import axios, { AxiosResponse } from 'axios';
import * as mutationTypes from './mutationTypes';

export const register = async (
  { dispatch }: { dispatch: Dispatch },
  details: Record<string, string>) => {
  // await axios.get('sanctum/csrf-cookie');
  // await axios.post('sanctum/register', details);
  let response = await axios.post('api/v1/user', details);

  let token = response.data.access_token;
  localStorage.setItem("token", token);

  return dispatch('user');
};

export const login = async (
  { dispatch }: { dispatch: Dispatch },
  credentials: Record<string, string>) => {
  // await axios.get('sanctum/csrf-cookie');
  let response = await axios.post('api/v1/auth/login', credentials);

  let token = response.data.access_token;
  localStorage.setItem("token", token);

  return dispatch('user');
};

export const logout = async ({ commit }: { commit: Commit }) => {
  // await axios.post('sanctum/logout');
  await axios.post('api/v1/auth/logout');

  commit(mutationTypes.SET_AUTHENTICATED, false);
  commit(mutationTypes.SET_USER, {});
};

export const user = (
  { commit }: { commit: Commit },
) => axios.post('api/v1/auth/me')
  .then((response: AxiosResponse) => {
    commit(mutationTypes.SET_AUTHENTICATED, true);
    commit(mutationTypes.SET_USER, response.data);
  }).catch(() => {
    commit(mutationTypes.SET_AUTHENTICATED, false);
    commit(mutationTypes.SET_USER, {});
  });

export const forgotPassword = async (
  { dispatch }: { dispatch: Dispatch },
  details: Record<string, string>) => {
  // await axios.get('sanctum/csrf-cookie');
  await axios.post('api/v1/password/email', details);

  return dispatch('user');
};

export const resetPassword = async (
  { dispatch }: { dispatch: Dispatch },
  details: Record<string, string>) => {
  // await axios.get('sanctum/csrf-cookie');
  await axios.post('api/v1/password/reset', details);

  return dispatch('user');
};

export const updatePassword = async (
  { dispatch }: { dispatch: Dispatch },
  details: Record<string, string>) => {
  // await axios.get('sanctum/csrf-cookie');
  await axios.post('api/v1/password/update', details);

  return dispatch('user');
};

export const setRegRole = ({ commit }: { commit: Commit }, role: string) => {
  commit(mutationTypes.SET_REG_ROLE, role);
};
