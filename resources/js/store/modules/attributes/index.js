import actions from './actions';
import getters from './getters';
import mutations from './mutations';

const state = {
    program: {},
    programs: {},
    tracks: {},
    concentrations: {},
    partners: {},
    searches: {},
    compare: {},
    view: 'programs',
    list: 'programs',
    active: ['TRUE']
};

export default {
    namespaced: true,
    state,
    actions,
    getters,
    mutations,
};