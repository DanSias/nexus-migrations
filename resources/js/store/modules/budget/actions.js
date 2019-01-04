import axios from 'axios';

const getYear = (context) => {
    axios
        .get('/url')
        .then(response => {
            context.dispatch('SET_YEAR', response.data);
        })
};

export default {
  getYear,
};