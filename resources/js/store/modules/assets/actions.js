import axios from 'axios';

const getAction = (context) => {
    axios
        .get('/url')
        .then(response => {
            context.dispatch('SET_PAGE', response.data);
        })
};

const setPage = (context, page) => {
    context.commit('SET_PAGE', page);
};

const setDomain = (context, domain) => {
    context.commit('SET_DOMAIN', domain);
};

const setChartPage = (context, page) => {
    context.commit('SET_CHART_PAGE', page);
};

const setLink = (context, link) => {
    context.commit('SET_LINK', link);
};

const gaEditDetails = (context, slug) => {
    if (context.state.gaPages && context.state.gaPages !== undefined) {
        let pro = context.state.gaPages.program
        let sch = pro.split('-');
        let domain = context.state.gaPages.domain.asset;
        let details = {
            program: pro,
            domain: domain,
            school: sch[0],
            slug: slug,
        };
        context.commit('SET_PAGE', details);
    }
};

const setUrl = (context, url) => {
    context.commit('SET_URL', url);
};

export default {
    getAction,
    setPage,
    setDomain,
    setChartPage,
    setLink,
    gaEditDetails,
    setUrl,
};