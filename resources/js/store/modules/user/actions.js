import axios from 'axios';

const getUser = (context) => {
    axios
        .get('/me')
        .then(response => {
            context.dispatch('SET_USER', response.data);
        })
};

export default {
  getUser,
};