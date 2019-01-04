const SET_DATA = (state, data) => {
    state.data = data;
};

const SET_HEADINGS = (state, headings) => {
    state.headings = headings;
};
const SET_ACTUALS = (state, actuals) => {
    state.actuals = actuals;
};
const SET_FORECAST = (state, forecast) => {
    state.forecast = forecast;
};
const SET_BUDGET = (state, budget) => {
    state.budget = budget;
};
const SET_PIPELINE = (state, pipeline) => {
    state.pipeline = pipeline;
};
const SET_SELECTED_CHANNEL = (state, channel) => {
    state.selectedChannel = channel;
};
const SET_CHANNEL_METRICS = (state, metrics) => {
    state.metricsByChannel = metrics;
};

// Metrics for expanded sidebar
const SET_EXPAND_BUDGET = (state, expand) => {
    state.expand.budget = expand.budget;
};
const SET_EXPAND_ACTUALS = (state, expand) => {
    state.expand.actuals = expand.actuals;
};
const SET_EXPAND_FORECAST = (state, expand) => {
    state.expand.forecast = expand.forecast;
};
const SET_EXPAND_PIPELINE = (state, expand) => {
    state.expand.pipeline = expand.pipeline;
};
const CLEAR_EXPAND_DATA = (state) => {
    state.expand.budget = {};
    state.expand.actuals = {};
    state.expand.forecast = {};
    state.expand.pipeline = {};
};

// Metrics for table
const SET_TABLE_BUDGET = (state, budget) => {
    state.table.budget = budget;
};
const SET_TABLE_ACTUALS = (state, actuals) => {
    state.table.actuals = actuals;
};
const SET_TABLE_FORECAST = (state, forecast) => {
    state.table.forecast = forecast;
};
const SET_TABLE_PIPELINE = (state, pipeline) => {
    state.table.pipeline = pipeline;
};

// Total Metrics
const SET_TOTAL_BUDGET = (state, budget) => {
    state.total.budget = budget;
};
const SET_TOTAL_ACTUALS = (state, actuals) => {
    state.total.actuals = actuals;
};
const SET_TOTAL_FORECAST = (state, forecast) => {
    state.total.forecast = forecast;
};
const SET_TOTAL_PIPELINE = (state, pipeline) => {
    state.total.pipeline = pipeline;
};

const CLEAR_TABLE_DATA = (state) => {
    state.table.budget = {};
    state.table.actuals = {};
    state.table.forecast = {};
};

export default {
    SET_DATA,
};