import actions from './actions';
import getters from './getters';
import mutations from './mutations';

const state = {
    year: 2019,
    status: 'closed',
    scenario: '',
    description: '',
    view: 'list',
    type: 'recruitment',
    selected: '',
    selectedChannel: '',
    selectedVertical: '',
    view: 'list',
    compare: {},
    actuals: {},
    actualsProgramTotal: {},
    actualsChannelVertical: {},

    terms: ['A1', 'A2', 'A3', 'B1', 'B2', 'B3', 'C1', 'C2', 'C3'],
    stages: [
        'contact',
        'insales',
        'app',
        'comfile',
        'acc',
        'accconf',
        'startsleadmonth'
    ],
    stageHeading: {
        contact: 'Contacted',
        insales: 'In Sales',
        app: 'Applications',
        comfile: 'Complete Files',
        acc: 'Accepted',
        accconf: 'Acc. Confirmed',
        startsleadmonth: 'Starts'
    },
    inputObject: {
        A1: {
            contact: 0,
            insales: 0,
            app: 0,
            comfile: 0,
            acc: 0,
            accconf: 0,
            startsleadmonth: 0
        }
    },
    workingBudget: {},
    channelInitiatives: {},
    workingInitiatives: {},
    budgetScenarios: {},
    baseline: {},
    programChannel: {},
    notes: {},
};

export default {
    namespaced: true,
    state,
    actions,
    getters,
    mutations,
};