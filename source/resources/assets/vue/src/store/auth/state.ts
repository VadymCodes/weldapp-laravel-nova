export type AuthState = {
  authenticated: boolean;
  user: Record<string, string>;
  regRole: string,
}

const state: AuthState = {
  authenticated: false,
  user: {},
  regRole: 'driver',
};

export default state;
