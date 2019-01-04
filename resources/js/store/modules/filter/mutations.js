const SET_PROGRAM_DETAILS = (state, payload) => {
    state.programs = payload;
};

const SET_SELECTED = (state, payload) => {
    state.selected = payload;
};

const SET_SELECTED_GROUP = (state, payload) => {
    state.selectedGroup = payload;
};

const SET_EXPAND_TYPE = (state, payload) => {
    state.expandType = payload;
};

const SET_EXPAND_SELECTED = (state, payload) => {
    state.expandSelected = payload;
};

const SET_FILTER_GROUP = (state, value) => {
    state.group = value;
};

const SET_FILTER_EXCLUDE_GROUP = (state, payload) => {
    state.excludeGroup = payload;
};

const SET_FILTER_EXCLUDE = (state, payload) => {
    state.exclude = payload;
};

const SET_FILTER_EXCLUDE_CHANNELS = (state, payload) => {
    state.excludeChannels = payload;
};

const SET_FILTER_PARTNER = (state, payload) => {
    state.academic_partner = payload;
};

const SET_FILTER_PROGRAM = (state, payload) => {
    state.program = payload;
};

const SET_FILTER_LOCATION = (state, payload) => {
    state.location = payload;
};

const SET_FILTER_BU = (state, payload) => {
    state.bu = payload;
};

const SET_FILTER_VERTICAL = (state, payload) => {
    state.vertical = payload;
};

const SET_FILTER_SUBVERTICAL = (state, payload) => {
    state.subvertical = payload;
};

const SET_FILTER_LEVEL = (state, payload) => {
    state.degree_level = payload;
};

const SET_FILTER_TYPE = (state, payload) => {
    state.degree_type = payload;
};

const SET_FILTER_CHANNEL = (state, payload) => {
    state.channel = payload;
};

const SET_FILTER_INITIATIVE = (state, payload) => {
    state.initiative = payload;
};

const SET_FILTER_LIST = (state, payload) => {
    state.list = payload;
};

const SET_FILTER_TERM_YEAR = (state, payload) => {
    state.termYear = payload;
};

const SET_FILTER_ICON = (state, payload) => {
    state.icon = payload;
};

const SET_FILTER_QUERY = (state, payload) => {
    state.query = payload;
};

const SET_FILTER_SORT = (state, payload) => {
    state.sort = payload;
};

const SET_FILTER_ORDER = (state, payload) => {
    state.order = payload;
};

const SET_FILTER_VINTAGE = (state, payload) => {
    state.vintage = payload;
};

const SET_FILTER_SEMESTER = (state, payload) => {
    state.semester = payload;
};

const SET_FILTER_TERM = (state, payload) => {
    state.term = payload;
};

const SET_FILTER_STRATEGY = (state, payload) => {
    state.strategy = payload;
};

const SET_BUDGET_TYPE = (state, payload) => {
    state.budgetType = payload;
};

const SET_FILTER_STARBUCKS = (state, payload) => {
    state.starbucks = payload;
};

const SET_FILTER_MINE = (state, payload) => {
    state.useMine = payload;
};

const TOGGLE_FILTER_MINE = (state, payload) => {
    state.useMine = !state.useMine;
};


export default {
    SET_SELECTED,
    SET_GROUP,
    SET_CHANNEL,
};