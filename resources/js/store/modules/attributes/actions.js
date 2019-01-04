import axios from 'axios';

const getPrograms = (context) => {
    axios
        .get('/url')
        .then(response => {
            context.dispatch('SET_PROGRAMS', response.data);
        })
};

const setProgram = (context, program) => {
    if (context.state.program == program) {
        program = {};
    } 
    context.commit('SET_PROGRAM', program);
};

const setPartner = (context, partner) => {
    context.commit('SET_PARTNER', partner);
};

const setCompare = (context, compare) => {
    context.commit('SET_COMPARE', compare);
};

const setView = (context, view) => {
    context.commit('SET_VIEW', view);
};

const setActive = (context, status) => {
    context.commit('SET_ACTIVE', status);
};



export default {
    getPrograms,
    setProgram,
    setPartner,
    setCompare,
    setView,
    setActive,
};