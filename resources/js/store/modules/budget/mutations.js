const SET_YEAR = (state, year) => {
    state.year = year;
};

const SET_SELECTED = (state, payload) => {
    state.selected = payload;
};

const SET_SELECTED_CHANNEL = (state, payload) => {
    state.selectedChannel = payload;
};

const SET_SELECTED_VERTICAL = (state, payload) => {
    state.selectedVertical = payload;
};

const SET_ACTUALS = (state, payload) => {
    state.actuals = payload;
};

const SET_ACTUALS_PROGRAM_TOTAL = (state, payload) => {
    state.actualsProgramTotal = payload;
};

const SET_ACTUALS_CHANNEL_VERTICAL = (state, payload) => {
    state.actualsChannelVertical = payload;
};

const NEW_INPUT_KEY = (state, payload) => {
    let key = payload.name;
    let obj = payload.object;
    state.inputObject[key] = obj;
};

const SET_INPUT_VALUE = (state, payload) => {
    let row = payload.row;
    if (!state.inputObject[row]) {
        state.inputObject[row] = {};
    }
    let col = payload.col;
    if (!state.inputObject[row][col]) {
        state.inputObject[row][col] = 0;
    }
    let val = payload.val;
    state.inputObject[row][col] = val;
};

const SET_INPUT_KEY = (state, payload) => {
    let key = payload.key;
    let val = payload.value;
    state.inputObject[key] = val;
};

const SET_INITIATIVE_VALUE = (state, payload) => {
    console.log(payload);
    let init = payload.init;
    let metric = payload.metric;
    let month = payload.month;
    let value = parseInt(payload.value);
    if (metric == 'leads' || metric == 'spend') {
        // state.workingInitiatives[init][metric][month] = value;
        Vue.set(state.workingInitiatives[init][metric], month, value);
        let leads = state.workingInitiatives[init]['leads'][month];
        let spend = state.workingInitiatives[init]['spend'][month];
        if (leads > 0 && spend > 0) {
            let cpl = spend / leads;
            // state.workingInitiatives[init]['cpl'][month] = cpl;
            Vue.set(state.workingInitiatives[init]['cpl'], month, cpl);
        }
    } else if (metric == 'cpl') {
        // state.workingInitiatives[init][metric][month] = value;
        Vue.set(state.workingInitiatives[init][metric], month, value);
        // let leads = state.workingInitiatives[init]['leads'][month];
        let spend = state.workingInitiatives[init]['spend'][month];
        // state.workingInitiatives[init]['leads'][month] = spend / value;
        Vue.set(state.workingInitiatives[init]['leads'], month, spend / value);
    }
};

// const SET_INITIATIVE_OBJECT = (state, payload) => {
//     let init = payload.initiative;
//     let obj = payload.object;
// };

const SET_WORKING_BUDGET = (state, payload) => {
    state.workingBudget = payload;
};

const CLEAR_INPUT_OBJECT = (state, payload) => {
    state.inputObject = {};
};

const SET_BUDGET_TYPE = (state, payload) => {
    state.type = payload;
};

const SET_CHANNEL_INITIATIVES = (state, payload) => {
    state.channelInitiatives = payload;
};

const SET_INITIATIVE_OBJECT = (state, payload) => {
    state.workingInitiatives[payload.key] = payload.value;
};

const SET_BUDGET_SCENARIOS = (state, payload) => {
    state.budgetScenarios = payload;
};

const SET_BUDGET_SETTINGS = (state, payload) => {
    if (payload.year) {
        state.year = payload.year;
    }
    if (payload.status) {
        state.status = payload.status.toLowerCase();
    }
    if (payload.scenario) {
        state.scenario = payload.scenario;
    }
    if (payload.description) {
        state.description = payload.description;
    }
};

const SET_BASELINE = (state, payload) => {
    state.baseline = payload;
};

const SET_PROGRAM_CHANNEL = (state, payload) => {
    state.programChannel = payload;
};

const SET_BUDGET_NOTES = (state, payload) => {
    state.notes = payload;
};

const RESET_WORKING_INITIATIVES = (state) => {
    state.workingInitiatives = {};
};

const DELETE_INITIATIVE = (state, payload) => {
    let init = payload;
    delete state.workingInitiatives[init];
};

 
export default {
    SET_YEAR,
};