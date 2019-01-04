import actions from './actions';
import getters from './getters';
import mutations from './mutations';

const state = {
    type: '',
    view: '',
    expand: '',
    messages: [],
    
    overview: {
        metric: 'leads',
        pipeline: 'contact',
    },
    show: {
        feedback: false,
        offCanvas: false
    }
};

export default {
    namespaced: true,
    state,
    actions,
    getters,
    mutations,
};