import axios from 'axios';

const setType = (context, type) => {
    context.commit("SET_PAGE_TYPE", type);
    context.dispatch('json/getInitialState', {}, { root: true });

    switch (type) {
        case 'conversica':
            context.commit('filter/SET_FILTER_CHANNEL', 'Cultivation', { root: true });
            context.commit('filter/SET_FILTER_INITIATIVE', 'Conversica', { root: true });
            context.dispatch('json/getConversicaOptions', {}, { root: true });
            context.dispatch('json/getConversicaDetails', {}, { root: true });
            break;
        case 'users':
            context.dispatch('json/getUserArray', {}, { root: true });
        case 'attributes':
        case 'map':
            context.dispatch('json/getPartnerAttributes', {}, { root: true });
        case 'budget':
            context.dispatch('json/getBudget', {}, { root: true });
            context.dispatch('json/workingBudget', {}, { root: true });
        case 'tracker':
        case 'leadership':
            context.dispatch('date/setRangeCalendarMonth', {}, { root: true });
        case 'lp':
            break;
        default:
            break;
    }
};
     
const setData = (context) => {
    let type = context.getters.type;
    switch (type) {
        case 'performance':
            context.dispatch('json/initializeData', {}, { root: true });
            context.dispatch('json/getRevenueData', {}, { root: true });
            context.dispatch('json/getTermConversionData', {}, { root: true });
            context.dispatch('json/getNotes', {}, { root: true });
            break;
        case 'overview':
            context.dispatch('json/initializeData', {}, { root: true });
            context.dispatch('json/getRevenueData', {}, { root: true });
            // context.dispatch('json/getTermConversionData', {}, { root: true });
            // context.dispatch('json/getActuals', {}, { root: true });
            break;
        case 'budget':
            context.dispatch('json/initializeData', {}, { root: true });
            context.dispatch('json/getBudgetScenarios', {}, { root: true });
            break;
        case 'conversica':
            context.dispatch('json/getConversicaData', {}, { root: true });
            // context.dispatch('json/getConversicaTargets', {}, { root: true });
            context.dispatch('filter/setFilterChannel', 'Cultivation', { root: true });
            break;
        case 'conversion':
        case 'benchmarks':
            context.dispatch('json/getConversionData', {}, { root: true });
            break;
        case 'me':
            context.dispatch('user/setMyForm', {}, { root: true });
            break;
        case 'tracker':
            let location = context.rootGetters['filter/location'];
            context.commit('filter/SET_FILTER_GROUP', 'program', { root: true });
            context.dispatch('json/initializeData', {}, { root: true });
            context.dispatch('json/getRevenueData', {}, { root: true });
            context.dispatch('json/getTermConversionData', {}, { root: true });
            context.dispatch('json/getChannelNotes', {channel: 'Account Management', location: location}, { root: true });
            break;
        case 'leadership':
            context.dispatch('json/initializeData', {}, { root: true });
            context.dispatch('json/getChannelNotes', { channel: 'ADMA', location: location }, { root: true });
            context.dispatch('json/getRevenueData', {}, { root: true });
            break;
        case 'lp':
            context.dispatch('json/getLandingPages', {}, { root: true });
            break;
    }
};

const setExpand = (context, payload) => {
    context.commit('SET_PAGE_EXPAND', payload);
    if (context.getters.type == 'overview') {
        if (payload == "leads") {
            let send = {};
            send.name = "Lead Overview";
            send.cols = [
                "name",
                "leads",
                "leadsBudgetPace",
                "leadsBudgetPaceDelta",
                "leadsForecastPace",
                "leadsForecastPaceDelta",
                "leadBudgetForecastDelta"
            ];
            context.dispatch("table/setColumnLayout", send, { root: true });
        } else if (payload == "contact") {
            let send = {};
            send.name = "Contact Overview";
            send.cols = [
                "name",
                "contact",
                "leads",
                "contactRate",
                "spend",
                "cpcontact"
            ];
            context.dispatch("table/setColumnLayout", send, { root: true });
        } else if (payload == "students") {
            context.dispatch("revenue/setRevenueMetric", "Students", { root: true });
        } else if (payload == "revenue") {
            context.dispatch("revenue/setRevenueMetric", "Revenue", { root: true });
        } else if (payload == "starts") {
            context.dispatch("revenue/setRevenueMetric", "Starts", { root: true });
        }
    }
};

const setView = (context, view) => {
    context.commit("SET_PAGE_VIEW", view);
};

const addMessage = (context, msg) => {
    context.commit('ADD_MESSAGE', msg);
};

const setFeedback = (context, feedback) => {
    if (feedback === true || feedback === false) {
        context.commit('SET_FEEDBACK', feedback);
    } else {
        feedback = ! context.state.show.feedback;
        context.commit('SET_FEEDBACK', feedback);
    }
};

const setOverviewMetric = (context, metric) => {
    context.commit('SET_OVERVIEW_METRIC', metric);
};

const setOverviewPipeline = (context, pipeline) => {
    context.commit('SET_OVERVIEW_PIPELINE', pipeline);
};

const saveFeedback = (context, feedback) => {
    axios
        .post('/feedback/submit', { 
            type: feedback.type,
            url: feedback.url,
            message: feedback.message
        })
        .then(response => {
            let data = response.data;
            return data;
        });
};


// User Actions
const userView = (context, view) => {
    // Set Location, BU, Channel
    let loc = view.focus_location;
    let bu = view.focus_business_unit;
    let ch = view.focus_channel;
    let programs = view.programs;
    if (programs.length > 0) {
        context.commit('filter/SET_FILTER_LIST', programs, { root: true });
        context.commit('filter/SET_FILTER_MINE', true, { root: true });
        context.commit('table/SET_TABLE_FILTER_GROUP', 'Program', { root: true });
    }
    context.commit('filter/SET_FILTER_LOCATION', loc, { root: true });
    context.commit('filter/SET_FILTER_BU', bu, { root: true });
    context.commit('filter/SET_FILTER_CHANNEL', ch, { root: true });

    // If location set, group by program
    if (loc != '' && loc != null ) {
        context.commit('filter/SET_FILTER_GROUP', 'Program', { root: true });
        context.commit('table/SET_TABLE_FILTER_GROUP', 'Program', { root: true });

    }
    context.dispatch('page/setPageData', {}, { root: true });
};

const resetUserFilter = (context) => {
    context.dispatch('filter/setFilterLocation', context.getters.myLocation, { root: true });
    context.dispatch('filter/setFilterBu', context.getters.myBu, { root: true });
    context.dispatch('filter/setFilterPartner', '', { root: true });
    context.dispatch('filter/setFilterVertical', '', { root: true });
    context.dispatch('filter/setFilterChannel', '', { root: true });
};

// Set form to current user
const setMyForm = (context) => {
    context.commit('SET_FORM_TEAM', context.getters.myTeam);
    context.commit('SET_FORM_TITLE', context.getters.myRole);
    context.commit('SET_FORM_LOCATION', context.getters.myLocation);
    context.commit('SET_FORM_BU', context.getters.myBu);
    context.commit('SET_FORM_CHANNEL', context.getters.myChannel);
    context.commit('SET_FORM_EXTENSION', context.getters.myExtension);
};


// Form: User Details
const setFormTeam = (context, payload) => {
    context.commit('SET_FORM_TEAM', payload);
};
const setFormTitle = (context, payload) => {
    context.commit('SET_FORM_TITLE', payload);
};
const setFormExtension = (context, payload) => {
    context.commit('SET_FORM_EXTENSION', payload);
};
const setFormLocation = (context, payload) => {
    context.commit('SET_FORM_LOCATION', payload);
};
const setFormBu = (context, payload) => {
    context.commit('SET_FORM_BU', payload);
};
const setFormChannel = (context, payload) => {
    context.commit('SET_FORM_CHANNEL', payload);
};

// Users Page
const setPageView = (context, payload) => {
    context.commit('SET_PAGE_VIEW', payload);
    context.commit('SET_PAGE_SELECTED', '');
    context.commit('SET_PAGE_SEARCH', '');
};
const setPageSelected = (context, payload) => {
    context.commit('SET_PAGE_SELECTED', payload);
    if (context.getters.pageView == 'Users') {
        let usr = context.getters.pageSelected;
        let userDetails = context.getters.users[usr];
        console.log(userDetails);
        context.commit('SET_FORM_TEAM', userDetails.team);
        context.commit('SET_FORM_TITLE', userDetails.role_title);
        context.commit('SET_FORM_LOCATION', userDetails.focus_location);
        context.commit('SET_FORM_BU', userDetails.focus_business_unit);
        context.commit('SET_FORM_CHANNEL', userDetails.focus_channel);
        context.commit('SET_FORM_EXTENSION', userDetails.extension);
    }
};
const setPageSearch = (context, payload) => {
    context.commit('SET_PAGE_SEARCH', payload);
};

// Profile Page
const setProfilePageSelected = (context, payload) => {
    context.commit('SET_PROFILE_PAGE_SELECTED', payload);
    context.commit('filter/SET_FILTER_MINE', false, { root: true });
};


export default {
    addMessage,
    saveFeedback,
    setType,
    setData,
    setExpand,
    setView,
    setFeedback,
    setOverviewMetric,
    setOverviewPipeline,

    userView,
    resetUserFilter,
    setMyForm,
    setFormTeam,
    setFormTitle,
    setFormExtension,
    setFormLocation,
    setFormBu,
    setFormChannel,
    setPageView,
    setPageSelected,
    setPageSearch,
    setProfilePageSelected,
};