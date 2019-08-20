import axios from 'axios';

const axiosInstance = axios.create({
    baseURL: HYDROFON.baseURL + '/api/',
    withCredentials: true
});

export default axiosInstance;
