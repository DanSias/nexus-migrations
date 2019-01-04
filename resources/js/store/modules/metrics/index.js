import actions from './actions';
import getters from './getters';
import mutations from './mutations';

import definitions from '../metrics';
import layouts from '../layouts';

const state = {
    definitions: definitions,
    // metrics: metricsYearMonth,
    layouts: layouts,
    headings: [
        'leads', 
        'spend', 
        'cpl'
    ],
    // Channel
    selectedChannel: '',
    // organized by year month
    actuals: {},
    budget: {},
    forecast: {},
    pipeline: {},
    // Expanded data
    expand: {
        actuals: {},
        budget: {},
        forecast: {},
        pipeline: {},
    },
    // Table Data
    table: {
        actuals: {},
        budget: {},
        forecast: {},
        pipeline: {},
    },
    total: {
        actuals: {},
        budget: {},
        forecast: {},
        pipeline: {},
    },
    metricsByChannel: {},
    millions: [
        'spend', 
        'spendBudget', 
        'spendForecast', 
        'spendBudgetDelta', 
        'spendForecastDelta', 
        'revenue'
    ]
};

export default {
    namespaced: true,
    state,
    actions,
    getters,
    mutations,
};