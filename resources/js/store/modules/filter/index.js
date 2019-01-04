import actions from './actions';
import getters from './getters';
import mutations from './mutations';

const state = {
    // Current Item 
    selected: '',
    selectedGroup: 'location',

    // How programs are grouped in data set
    group: 'location',
    // Expanding beneath selected item
    expandType: '',
    expandSelected: '',

    programs: {},

    // Exclude items by group / channel
    excludeGroup: 'location',
    exclude: [],
    excludeChannels: [],

    // Program Attributes
    location: [],
    bu: [],
    partner: [],
    program: [],
    level: [],
    type: [],
    vertical: [],
    subvertical: [],
    active: ['TRUE'],
    channel: [],
    initiative: [],
    query: '',
    sort: '',
    order: '',
    vintage: '',
    list: [],
    useMine: false,
    // Semester / Term
    termYear: 2018,
    semester: '',
    term: '',
    // Budget Type
    budgetType: 'current',
    strategy: '',
    starbucks: false,
};

export default {
    namespaced: true,
    state,
    actions,
    getters,
    mutations,
};