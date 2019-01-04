import actions from './actions';
import getters from './getters';
import mutations from './mutations';

const state = {
    page: {
        id: '',
        domain: '',
        slug: '',
        school: '',
        program: '',
        channel: '',
        initiative: '',
        status: '',
        type: '',
        audience: '',
        left: '',
        comments: '',
    },
    pages: {},
    
    // Google Analytics
    gaPages: {},
    gaData: {},
    gaChart: {
        component: 'Total',
        metric: 'sessions',
        page: {},
    },
    domain: {
        id: '',
        program: '',
        channel: '',
        domain: '',
        partner_url: '',
        analytics_ua: '',
        analytics_view: 0
    },
    domains: {},
    link: {
        id: '',
        program: '',
        ap_url: '',
        page_title: '',
        href: '',
        anchor: '',
        date: '',
        correct: '',
        type: '',
        comments: ''
    },
    links: {},
    url: {},
    referralPages: {},
    pageLinks: {},
};

export default {
    namespaced: true,
    state,
    actions,
    getters,
    mutations,
};