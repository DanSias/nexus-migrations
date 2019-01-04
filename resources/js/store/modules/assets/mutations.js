const SET_PAGE = (state, page) => {
    state.page = page;
};

const SET_CHART_PAGE = (state, page) => {
    state.gaChart.page = page;
};

// Individual Page Elements for Form
const SET_PAGE_ID = (state, id) => {
    state.page.id = id;
};
const SET_PAGE_DOMAIN = (state, domain) => {
    state.page.domain = domain;
};
const SET_PAGE_SLUG = (state, slug) => {
    state.page.slug = slug;
};
const SET_PAGE_SCHOOL = (state, school) => {
    state.page.school = school;
};
const SET_PAGE_PROGRAM = (state, program) => {
    state.pageprogram = program;
};
const SET_PAGE_CHANNEL = (state, channel) => {
    state.page.channel = channel;
};
const SET_PAGE_INITIATIVE = (state, initiative) => {
    state.page.initiative = initiative;
};
const SET_PAGE_STATUS = (state, status) => {
    state.page.status = status;
};
const SET_PAGE_TYPE = (state, type) => {
    state.page.type = type;
};
const SET_PAGE_AUDIENCE = (state, audience) => {
    state.page.audience = audience;
};
const SET_PAGE_LEFT = (state, left) => {
    state.page.left = left;
};
const SET_PAGE_COMMENTS = (state, comments) => {
    state.page.comments = comments;
};


// Landing Page Data
const SET_LANDING_PAGES = (state, pages) => {
    state.pages = pages;
};
const SET_GA_PAGES = (state, gaPages) => {
    state.gaPages = gaPages;
};
const SET_GA_DATA = (state, gaData) => {
    state.gaData = gaData;
};
const SET_CHART_METRIC = (state, metric) => {
    state.gaChart.metric = metric;
};
const SET_CHART_COMPONENT = (state, component) => {
    state.gaChart.component = component;
};
const SET_DOMAINS = (state, domains) => {
    state.domains = domains;
};
const SET_DOMAIN = (state, domain) => {
    state.domain = domain;
};


// Individual Domain Elements
const SET_DOMAIN_ID = (state, id) => {
    state.domain.id = id;
};
const SET_DOMAIN_PROGRAM = (state, program) => {
    state.domain.program = program;
};
const SET_DOMAIN_DOMAIN = (state, domain) => {
    state.domain.domain = domain;
};
const SET_DOMAIN_PARTNER_URL = (state, url) => {
    state.domain.partner_url = url;
};
const SET_DOMAIN_UA = (state, ua) => {
    state.domain.analytics_ua = ua;
};
const SET_DOMAIN_VIEW = (state, view) => {
    state.domain.analytics_view = view;
};

const SET_LINKS = (state, links) => {
    state.links = links;
};
const SET_LINK = (state, link) => {
    state.link = link;
};
const SET_LINK_ID = (state, id) => {
    state.link.id = id;
};
const SET_LINK_PROGRAM = (state, program) => {
    state.link.program = program;
};
const SET_LINK_AP_URL = (state, url) => {
    state.link.ap_url = url;
};
const SET_LINK_PAGE_TITLE = (state, title) => {
    state.link.page_title = title;
};
const SET_LINK_HREF = (state, href) => {
    state.link.href = href;
};
const SET_LINK_ANCHOR = (state, anchor) => {
    state.link.anchor = anchor;
}; 
const SET_LINK_DATE = (state, date) => {
    state.link.date = date;
};
const SET_LINK_CORRECT = (state, correct) => {
    state.link.correct = correct;
};
const SET_LINK_TYPE = (state, type) => {
    state.link.type = type;
};
const SET_LINK_COMMENTS = (state, comments) => {
    state.link.comments = comments;
};

const SET_REFERRAL_PAGES = (state, pages) => {
    state.referralPages = pages;
};
const SET_URL = (state, url) => {
    state.url = url;
};

const SET_PAGE_LINKS = (state, links) => {
    state.pageLinks = links;
};

export default {
    SET_PAGE,
    SET_CHART_PAGE,
    SET_PAGE_ID,
    SET_PAGE_DOMAIN,
    SET_PAGE_SLUG,
    SET_PAGE_SCHOOL,
    SET_PAGE_PROGRAM,
    SET_PAGE_CHANNEL,
    SET_PAGE_INITIATIVE,
    SET_PAGE_STATUS,
    SET_PAGE_TYPE,
    SET_PAGE_AUDIENCE,
    SET_PAGE_LEFT,
    SET_PAGE_COMMENTS,

    SET_LANDING_PAGES,
    SET_GA_PAGES,
    SET_GA_DATA,
    SET_CHART_METRIC,
    SET_CHART_COMPONENT,
    SET_DOMAINS,
    SET_DOMAIN,

    SET_DOMAIN_ID,
    SET_DOMAIN_PROGRAM,
    SET_DOMAIN_DOMAIN,
    SET_DOMAIN_PARTNER_URL,
    SET_DOMAIN_UA,
    SET_DOMAIN_VIEW,
    SET_LINKS,
    SET_LINK,
    SET_LINK_ID,
    SET_LINK_PROGRAM,
    SET_LINK_AP_URL,
    SET_LINK_PAGE_TITLE,
    SET_LINK_HREF,
    SET_LINK_ANCHOR,
    SET_LINK_DATE,
    SET_LINK_CORRECT,
    SET_LINK_TYPE,
    SET_LINK_COMMENTS,
    SET_REFERRAL_PAGES,
    SET_URL,
    SET_PAGE_LINKS,
};