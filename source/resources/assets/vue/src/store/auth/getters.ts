import { AuthState } from './state';

export const authenticated = (state: AuthState): boolean => state.authenticated;
export const user = (state: AuthState): Record<string, string> => state.user;
export const regRole = (state: AuthState): string => state.regRole;
