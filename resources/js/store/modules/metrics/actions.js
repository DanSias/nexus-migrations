import axios from 'axios';

const getAction = (context) => {
    axios
        .get('/url')
        .then(response => {
            context.dispatch('SET_DATA', response.data);
        })
};

export default {
  getAction,
};