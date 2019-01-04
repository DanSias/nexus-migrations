const SET_PROGRAMS = (state, programs) => {
    state.programs = programs;
};

const SET_PARTNERS = (state, partners) => {
    state.partners = partners;
};

const SET_COMPARE = (state, compare) => {
    state.compare = compare;
};

const SET_VIEW = (state, view) => {
    state.view = view;
};

const SET_PROGRAM = (state, program) => {
    state.program = program;
};

const SET_ACTIVE = (state, active) => {
    state.active = active;
};


export default {
    SET_PROGRAMS,
    SET_PARTNERS,
    SET_COMPARE,
    SET_VIEW,
    SET_PROGRAM,
    SET_ACTIVE
};