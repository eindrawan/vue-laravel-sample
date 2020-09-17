import axios from "axios";

const api = axios.create({
  baseURL: "http://localhost:8000/api",
  headers: {
    "Content-type": "application/json"
  }
});
api.interceptors.request.use(
  config => {
    if (http.token) {
      config.headers.authorization = `Bearer ${http.token}`;
    }
    return config;
  },
  err => Promise.reject(err)
);
let http = {
  token: "",
  $api: api
};

export default http;
