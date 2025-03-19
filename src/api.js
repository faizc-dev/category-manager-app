import axios from 'axios';

export const API_BASE_URL = 'http://localhost:8000/api'; // ✅ Export the constant

const api = axios.create({
  baseURL: API_BASE_URL,
  headers: {
    'Content-Type': 'application/json',
  },
});

export default api;
