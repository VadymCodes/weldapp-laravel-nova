import { GlobalState } from './state';

export const validationErrors = (state: GlobalState): [] => state.validationErrors;
export const hasError = (state: GlobalState): boolean => {
  if (state.error === '') {
    return false;
  }

  return true;
};
export const error = (state: GlobalState): string => state.error;
export const loaded = (state: GlobalState): boolean => state.loaded;
