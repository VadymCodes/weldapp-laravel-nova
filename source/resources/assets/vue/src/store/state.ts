export type GlobalState = {
  validationErrors: [];
  error: string;
  loaded: boolean;
};

const state: GlobalState = {
  validationErrors: [],
  error: '',
  loaded: false,
};

export default state;
