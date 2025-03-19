import { createStore } from 'vuex';
import api from '../api'; // Import the API instance

export default createStore({
  state: {
    user: JSON.parse(localStorage.getItem('user')) || null,
    token: localStorage.getItem('token') || null,
    profiles: [],
    categories: [],
    subcategories: []
  },
  mutations: {
    setUser(state, user) {
      state.user = user;
      localStorage.setItem('user', JSON.stringify(user)); 
    },
    setToken(state, token) {
      state.token = token;
      localStorage.setItem('token', token);
    },
    logout(state) {
      state.user = null;
      state.token = null;
      localStorage.removeItem('token');
    },
    setProfiles(state, profiles) {
      state.profiles = profiles;
    },
    setCategories(state, categories) {
      state.categories = categories;
    },
    setSubcategories(state, subcategories) {
      state.subcategories = subcategories;
    }
  },
  actions: {
    async login({ commit }, credentials) {
      try {
        const response = await api.post('/login.php', credentials);
        if (response.data.success) {
          commit('setUser', response.data.user);
          commit('setToken', response.data.token);
        } else {
          console.error(response.data.error);
        }
      } catch (error) {
        console.error('Login failed:', error);
      }
    },
    async register(_, userDetails) {
      try {
        const response = await api.post('/register.php', userDetails);
        if (response.data.success) {
          console.log('Registration successful:', response.data.message);
        } else {
          console.error('Registration failed:', response.data.error);
        }
      } catch (error) {
        console.error('Registration error:', error);
      }
    },
    logout({ commit }) {
      commit('logout');
    },
    async fetchProfiles({ commit }, email = null) {
      try {
        const response = await api.get(`/profiles.php${email ? `?email=${encodeURIComponent(email)}` : ''}`);

        commit('setProfiles', response.data);
      } catch (error) {
        console.error('Error fetching profiles:', error);
      }
    }
  },
  getters: {
    isAuthenticated: state => !!state.token,
    getUser: state => state.user,
    getProfiles: state => state.profiles,
    getCategories: state => state.categories,
    getSubcategories: state => state.subcategories
  }
});
