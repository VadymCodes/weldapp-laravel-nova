import axios from 'axios';
import store from '@/store';
import router from '@/router';
import { StatusCodes } from 'http-status-codes';

// Add a request interceptor
axios.interceptors.request.use(function (config) {
    const token = localStorage.getItem("token");
    config.headers.Authorization =  "Bearer " + token;

    return config;
});

axios.interceptors.response.use(
  (response) => response,
  (error) => {
    if (error.response.status === StatusCodes.UNAUTHORIZED) {
      store.dispatch('setError', error.response.data.error.message);
    }

    if (error.response.status === StatusCodes.UNPROCESSABLE_ENTITY) {
      store.dispatch('setValidationErrors', error.response.data.errors);
    }

    if (error.response.status === StatusCodes.FORBIDDEN) {
      store.dispatch('setError', error.response.data.message);
      // router.push({ name: 'home' });
    }

    if (error.response.status === StatusCodes.NOT_FOUND) {
      router.push({ name: 'home' });
    }

    return Promise.reject(error);
  },
);
