const SET_TYPE = (state, type) => {
    state.page.type = type;
};
const SET_VIEW = (state, view) => {
    state.page.view = view;
};
const SET_EXPAND = (state, expand) => {
    state.page.expand = expand;
};

const SET_OVERVIEW_METRIC = (state, metric) => {
    state.overview.metric = metric;
};
const SET_OVERVIEW_PIPELINE = (state, pipeline) => {
    state.overview.pipeline = pipeline;
};

const ADD_MESSAGE = (state, message) => {
    state.messages.push(message);
    setTimeout(() => {
        state.messages = []
    }, 4000);
};

const SET_FEEDBACK = (state, feedback) => {
    state.show.feedback = feedback;
};
const SET_OFFCANVAS = (state, offCanvas) => {
    state.show.offCanvas = offCanvas;
};


const SET_USER = (state, payload) => {
    state.user = payload;
};
const SET_USER_ARRAY = (state, payload) => {
    state.users = payload;
};

// Form Values
const SET_FORM_TEAM = (state, team) => {
    state.userForm.team = team;
};
const SET_FORM_TITLE = (state, title) => {
    state.userForm.role_title = title;
};
const SET_FORM_EXTENSION = (state, ext) => {
    state.userForm.extension = ext;
};
const SET_FORM_LOCATION = (state, location) => {
    state.userForm.focus_location = location;
};
const SET_FORM_BU = (state, bu) => {
    state.userForm.focus_business_unit = bu;
};
const SET_FORM_CHANNEL = (state, channel) => {
    state.userForm.focus_channel = channel;
};

// Users Page
const SET_PAGE_VIEW = (state, view) => {
    state.usersPage.view = view;
};
const SET_PAGE_SELECTED = (state, selected) => {
    state.usersPage.select = selected;
};
const SET_PAGE_SEARCH = (state, search) => {
    state.usersPage.search = search;
};

// Profile Page
const SET_PROFILE_PAGE_SELECTED = (state, selected) => {
    state.profilePage.selected = selected;
};

export default {
    SET_TYPE,
    SET_VIEW,
    SET_EXPAND,
    SET_OVERVIEW_METRIC,
    SET_OVERVIEW_PIPELINE,
    ADD_MESSAGE,
    SET_FEEDBACK,
    SET_OFFCANVAS,
};