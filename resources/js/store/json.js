import axios from 'axios';

export const json = {
    namespaced: true,

    getters: {
        buildFilter: (state, rootState, getters, rootGetters) => (filter) => {
            let startingFilter = rootGetters['filter/filter'];
            let selected = (filter.selected) ? filter.selected : rootGetters['filter/selected'];
            let returnFilter = Object.assign({}, startingFilter);
            if (filter === undefined ) {
                return startingFilter;
            }
            // If object has program / channel, set them
            if (filter.location) {
                returnFilter.location = filter.location;
            } else if (filter.Location) {
                returnFilter.location = filter.Location;
            }

            if (filter.program) {
                returnFilter.program = filter.program;
            }
            if (filter.Program) {
                returnFilter.program = filter.Program;
            }

            if (filter.year) {
                returnFilter.year = filter.year;
            }

            if (filter.channel) {
                returnFilter.channel = filter.channel;
            }
            if (filter.group) {
                returnFilter.group = filter.group;
            }
            if (filter.channel == '') {
                returnFilter.channel = '';
            }
            if (filter.destination == 'vertical') {
                returnFilter.location = '';
                returnFilter.business_unit = '';
                returnFilter.vertical = filter.vertical;
                returnFilter.group = 'vertical'
            }
            // Currently selected item
            returnFilter.selected = selected;

            if (filter.selectedGroup) {
                returnFilter.selectedGroup = filter.selectedGroup;
            }

            return returnFilter;
        }
    },

    actions: {
        getInitialState(context) {
            axios
                .get('/initialize')
                .then(response => {
                    // User 
                    context.commit('user/SET_USER', response.data.user, { root: true });
                    context.dispatch('user/userView', response.data.user, { root: true });
                    // Selects
                    context.commit('selects/SELECT_OPTIONS', response.data.selects, { root: true });
                    // Dates
                    context.commit('date/SET_DATES', response.data.dates, { root: true });
                    // Programs
                    context.commit('attributes/SET_PROGRAM_ATTRIBUTES', response.data.attributes, { root: true });
                });
        },
        // user Details
        getUser(context) {
            axios.get('/me-json').then(response => {
                context.commit('user/SET_USER', response.data, { root: true });
                context.dispatch('user/userView', response.data, { root: true });
            });
        },

        // All User Detailss
        getUserArray(context) {
            axios.get('/users-json').then(response => {
                context.commit('user/SET_USER_ARRAY', response.data, { root: true });
            });
        },
        
        // Select Options (school, vertical, etc)
        getSelectOptions(context) {
            axios.get('/select-options').then(response => {
                context.commit('selects/SELECT_OPTIONS', response.data, { root: true });
            });
        },
        // Specific to Conversica Programs
        getConversicaOptions(context) {
            axios.get('/conversica-selects').then(response => {
                context.commit('selects/SELECT_OPTIONS', response.data, { root: true });
            });
        },
        // Attributes for Conversica Programs
        getConversicaDetails(context) {
            axios.get('/conversica-details').then(response => {
                let attributes = {
                    programs: response.data
                };
                context.commit('attributes/SET_PROGRAM_ATTRIBUTES', attributes, { root: true });
            });
        },

        // Updated Dates
        getDates(context) {
            // date
            axios.get('/dates-json').then(response => {
                context.commit('date/SET_DATES', response.data, { root: true });
            });
        },

        // Program  and Partner Attributes
        getProgramAttributes(context) {
            axios.get('/attributes-json').then(response => {
                context.commit('attributes/SET_PROGRAM_ATTRIBUTES', response.data, { root: true });
            });
        },
        getPartnerAttributes(context) {
            axios.get('/partners-json').then(response => {
                context.commit('attributes/SET_PARTNER_ATTRIBUTES', response.data, { root: true });
            });
        },

        // Conversica (Actuals, Targets, Deployments)
        getConversicaData(context, payload) {
            context.commit('loading/SET_LOADING', 'conversica', { root: true });
            
            axios
                .get('/conversica/data', {
                    params: {
                        filter: context.rootGetters['filter/filter']
                    }
                })
                .then(response => {
                    context.commit('conversica/CONVERSICA_DATA', response.data.actuals, { root: true });
                    context.commit('conversica/CONVERSICA_TARGETS', response.data.targets, { root: true });
                    context.commit('conversica/CONVERSICA_DEPLOYMENTS', response.data.deployments, { root: true });

                    context.commit('loading/UNSET_LOADING', 'conversica', { root: true });
                });
        },

        // Conversica Targets (contact15 & quality30)
        getConversicaTargets(context, payload) {
            // context.commit('loading/SET_LOADING', 'conversica', { root: true });
            axios
                .get('/conversica-targets-json', {
                    params: {
                        filter: context.rootGetters['filter/filter']
                    }
                })
                .then(response => {
                    context.commit('conversica/CONVERSICA_TARGETS', response.data, { root: true });
                    // context.commit('loading/UNSET_LOADING', 'conversica', { root: true });
                });
        },

        // Primary Table
        getTableData(context, payload) {
            context.commit('loading/SET_LOADING', 'metrics', { root: true });
            let filter = context.getters.buildFilter(payload);

            axios
                .get('/metrics/data', {
                    params: {
                        filter: filter
                    }
                })
                .then(response => {
                    context.commit('table/SET_TABLE_BUDGET', response.data.budget, { root: true });
                    context.commit('table/SET_TABLE_ACTUALS', response.data.actuals, { root: true });
                    context.commit('table/SET_TABLE_FORECAST', response.data.forecast, { root: true });
                    context.commit('loading/UNSET_LOADING', 'metrics', { root: true });
                });
        },

        getActuals(context, payload) {
            context.commit('loading/SET_LOADING', 'actuals', { root: true });
            let filter = context.rootGetters['filter/filter'];
            axios
                .get('/metrics/actuals', {
                    params: {
                        filter
                    }
                })
                .then(response => {
                    context.commit('metrics/SET_ACTUALS', response.data.actuals, { root: true });
                    context.commit('table/SET_TABLE_ACTUALS', response.data.actuals, { root: true });
                    context.commit('loading/UNSET_LOADING', 'actuals', { root: true });
                });
        },
        getBudget(context, payload) {
            let filter = context.rootGetters['filter/filter'];
            axios
                .get('/metrics/budget', {
                    params: {
                        filter
                    }
                })
                .then(response => {
                    context.commit('metrics/SET_BUDGET', response.data.budget, { root: true });

                    context.commit('table/SET_TABLE_BUDGET', response.data.budget, { root: true });
                });
        },
        getForecast(context, payload) {
            let filter = context.rootGetters['filter/filter'];
            axios
                .get('/metrics/forecast', {
                    params: {
                        filter
                    }
                })
                .then(response => {
                    context.commit('metrics/SET_FORECAST', response.data.forecast, { root: true });
                    context.commit('table/SET_TABLE_FORECAST', response.data.forecast, { root: true });
                });
        },
        getPipeline(context, payload) {
            let filter = context.rootGetters['filter/filter'];
            axios
                .get('/metrics/pipeline', {
                    params: {
                        filter
                    }
                })
                .then(response => {
                    context.commit('metrics/SET_PIPELINE', response.data.pipeline, { root: true });
                    context.commit('table/SET_TABLE_PIPELINE', response.data.pipeline, { root: true });
                });
        },
        getActualsForBudgeting(context, payload) {
            let filter = context.rootGetters['filter/filter'];
            axios
                .get('/metrics/actuals', {
                    params: {
                        filter
                    }
                })
                .then(response => {
                    if (payload.destination == 'actuals') {
                        context.commit('budget/SET_ACTUALS', response.data.actuals, { root: true });
                    } else if (payload.destination == 'program') {
                        context.commit('budget/SET_ACTUALS_PROGRAM_TOTAL', response.data, { root: true });
                    } else if (payload.destination == 'vertical') {
                        context.commit('budget/SET_ACTUALS_CHANNEL_VERTICAL', response.data, { root: true });
                    }
                });
        },

        getBudgetScenarios(context, payload) {
            // let filter = context.getters.buildFilter(payload);
            let location = context.rootGetters['filter/location'];
            let bu = context.rootGetters['filter/bu'];
            let channel = context.rootGetters['filter/channel'];
            axios
                .get('/budget/scenarios', {
                    params: {
                        location,
                        bu,
                        channel
                    }
                })
                .then(response => {
                    context.commit('budget/SET_BUDGET_SCENARIOS', response.data, { root: true });
                });
        },

        getMetricsData(context, payload) {
            let filter = payload;
            
            filter = context.getters.buildFilter(filter);

            axios
                .get('/metrics/data', {
                    params: {
                        filter: filter
                    }
                })
                .then(response => {
                    context.commit('metrics/SET_BUDGET', response.data.budget, { root: true });
                    context.commit('metrics/SET_ACTUALS', response.data.actuals, { root: true });
                    context.commit('metrics/SET_FORECAST', response.data.forecast, { root: true });

                });
        },

        getExpandData(context, payload) {
            context.commit('metrics/CLEAR_EXPAND_DATA', {}, { root: true });
            let filter = context.getters.buildFilter(payload);
            console.log(payload);
            axios
                .get('/metrics/data', {
                    params: {
                        filter: filter
                    }
                })
                .then(response => {
                    context.commit('metrics/SET_EXPAND_BUDGET', response.data.budget, { root: true });
                    context.commit('metrics/SET_EXPAND_ACTUALS', response.data.actuals, { root: true });
                    context.commit('metrics/SET_EXPAND_FORECAST', response.data.forecast, { root: true });
                    context.commit('metrics/SET_EXPAND_PIPELINE', response.data.pipeline, { root: true });
                });
        },
        saveFeedback(context, payload) {
            axios
                .post('/feedback/submit', { 
                    type: payload.type,
                    url: payload.url,
                    message: payload.message
                })
                .then(response => {
                    let data = response.data;
                    return data;
                });
        },
        archiveFeedback(context, payload) {
            axios
                .post('/feedback/archive', {
                    id: payload
                })
                .then(response => {
                    let data = response.data;
                    return data;
                });
        },

        // Recruitment Budget
        saveBudget(context, payload) {
            axios
                .post('/budget/recruitment-rates', { input: payload })
                .then(response => {
                    let data = response.data;
                    console.log(data);
                    // context.dispatch('workingBudget');
                    context.dispatch('json/workingBudget', {}, { root: true });
                });
        },

        workingBudget(context, payload) {
            let filter = context.getters.buildFilter(payload);
            axios
                .get('/recruitment-budget-rates-json', {
                    params: {
                        filter: filter
                    }
                })
                .then(response => {
                    context.commit('budget/SET_WORKING_BUDGET', response.data, { root: true });
                });
        },

        // Channel / ADMA Notes
        getNotes(context, payload) {
            context.commit('loading/SET_LOADING', 'notes', { root: true });
            let pro = context.rootGetters['filter/selected'];
            if (pro == '') {
                pro = context.rootGetters['filter/location'];
            }

            axios
                .get('/notes-json', { 
                    params: { 
                        program: pro 
                    } 
                })
                .then(response => {
                    context.commit('notes/NOTES', response.data, { root: true });
                    context.commit('loading/UNSET_LOADING', 'notes', { root: true });
                });
        },
        // Notes for the table (newest by group item)
        getTableNotes(context) {
            let filter = context.rootGetters['filter/filter'];
            axios
                .get('/notes', {
                    params: {
                        filter
                    }
                })
                .then(response => {
                    context.commit('notes/SET_TABLE_NOTES', response.data, { root: true })
                });
        },
        getChannelNotes(context, payload) {
            context.commit('loading/SET_LOADING', 'notes', { root: true });

            let program = payload.program;
            let channel = payload.channel;
            let location = payload.location;

            axios
                .get('/notes-channel/json', {
                    params: {
                        program,
                        channel,
                        location
                    }
                })
                .then(response => {
                    context.commit('notes/NOTES', response.data, { root: true });
                    context.commit('loading/UNSET_LOADING', 'notes', { root: true });
                });
        },

        // Tasks (Action Items)
        getActionItems(context, payload) {
            context.commit('loading/SET_LOADING', 'actionItems', { root: true });
            let filter = context.getters.buildFilter(payload);
            axios
                .get('/tasks/json', {
                    params: {
                        filter
                    }
                })
                .then(response => {
                    context.commit('tasks/ACTION_ITEMS', response.data, { root: true });
                    context.commit('loading/UNSET_LOADING', 'actionItems', { root: true });
                });
        },

        // Conversion
        getConversionData(context, payload) {
            context.commit('loading/SET_LOADING', 'conversion', { root: true });
            
            axios
                .get('/cvrs-data', {
                    params: {
                        filter: context.rootGetters['filter/filter']
                    }
                })
                .then(response => {
                    context.commit('conversion/SET_CVRS', response.data, { root: true });
                    context.commit('loading/UNSET_LOADING', 'conversion', { root: true });
                });
        },
        // Term Conversion Report
        getTermConversionData(context, payload) {
            context.commit('loading/SET_LOADING', 'conversion', { root: true });
            let filter = context.getters.buildFilter(payload);
            axios
                .get('/term-conversion-json', {
                    params: {
                        filter: filter,
                    }
                })
                .then(response => {
                    context.commit('conversion/SET_TERM_CONVERSION', response.data, { root: true });
                    context.commit('loading/UNSET_LOADING', 'conversion', { root: true });
                });
        },

        // Comparison (Recruitment Dashboard)
        getComparisonData(context, payload) {
            context.commit('loading/SET_LOADING', 'comparison', { root: true });

            axios
                .get('/recruitment-json', {
                    params: {
                        filter: context.rootState['comparison/comparison']
                    }
                })
                .then(response => {
                    context.commit('comparison/SET_COMPARISON_DATA', response.data, { root: true });
                    context.commit('loading/UNSET_LOADING', 'comparison', { root: true });
                });
        },
        // Comparison Starts
        getComparisonStarts(context, payload) {
            axios
                .get('/recruitment-starts-json', {
                    params: {
                        filter: context.rootState['comparison/comparison']
                    }
                })
                .then(response => {
                    context.commit('comparison/SET_COMPARISON_STARTS', response.data, { root: true });
                });
        },
        // Program Deadlines
        getComparisonDeadlines(context, payload) {
            axios
                .get('/deadlines-json', {
                    params: {
                        filter: context.rootState['comparison/comparison']
                    }
                })
                .then(response => {
                    context.commit('comparison/SET_COMPARISON_DEADLINES', response.data, { root: true });
                });
        },

        // Comparison Tool
        getComparisonSelects(context, payload) {
            axios
                .get('/context-selects')
                .then(response => {
                    context.commit('comparison/SET_COMPARISON_SELECTS', response.data, { root: true });
                });
        },
        getComparisonToolData(context, payload) {
            axios
                .get('/context-json')
                .then(response => {
                    context.commit('comparison/SET_COMPARISON_TOOL_DATA', response.data, { root: true });
                });
        },

        // Revenue 
        getRevenueData(context, payload) {
            context.commit('loading/SET_LOADING', 'revenue', { root: true });
            let filter = context.getters.buildFilter(payload);
            filter.year = context.rootGetters['filter/termYear'];
            axios
                .get('/revenue-json', {
                    params: {
                        filter: filter,
                        selected: context.rootGetters['filter/selected']
                    }
                })
                .then(response => {
                    context.commit('revenue/REVENUE_DATA', response.data.thisYear, { root: true });
                    context.commit('revenue/REVENUE_DATA_LAST_YEAR', response.data.lastYear, { root: true });
                    context.commit('loading/UNSET_LOADING', 'revenue', { root: true });
                });
        },

        // Starts Pacing
        getStartsPacingData(context, payload) {
            axios
                .get('/starts-pacing-json', {
                    params: {
                        filter: context.rootGetters['filter/filter'],
                        pacingFilter: context.rootState['revenue/startsPacingFilter']
                    }
                })
                .then(response => {
                    context.commit('revenue/STARTS_PACING_DATA', response.data.thisYear, { root: true });
                    context.commit('revenue/STARTS_PACING_DATA_LAST_YEAR', response.data.lastYear, { root: true });
                });
        },


        initializeData(context) {
            // context.dispatch('getMetricsData');
            context.dispatch('getActuals');
            // context.dispatch('getConversicaData');
            context.dispatch('getBudget');
            context.dispatch('getForecast');
            context.dispatch('getPipeline');
            context.dispatch('getTableNotes');
        },

        // Channel Initiatives for Budgeting
        getBudgetInitiatives(context, payload) {
            axios
                .get('/budget/initiative-list', {
                    params: {
                        program: context.rootGetters['budget/selected'],
                    }
                })
                .then(response => {
                    context.commit('budget/SET_CHANNEL_INITIATIVES', response.data, { root: true });
                });
        },

        // Marketing Budget by Initiative
        saveInitiativeBudget(context, payload) {
            axios
                .post('/budget/save', { 
                    payload 
                })
                .then(response => {
                    let data = response.data;
                    // console.log(data);
                    // context.dispatch('workingBudget');
                    // context.dispatch('json/workingBudget', {}, { root: true });
                });
        },

        getBudgetSettings(context, payload) {
            console.log('get budget settings');
            axios.get('/budget/settings').then(response => {
                context.commit('budget/SET_BUDGET_SETTINGS', response.data, { root: true });
            });
        },
        getBudgetNotes(context, payload) {
            let filterChannel = context.rootGetters['filter/channel'];
            let budgetChannel = context.rootGetters['budget/channel'];
            let channel = filterChannel;
            if (filterChannel == '') {
                channel = budgetChannel;
            }

            let filterSelected = context.rootGetters['filter/selected'];
            let budgetSelected = context.rootGetters['budget/selected'];
            let selected = filterSelected;
            if (filterSelected == '') {
                selected = budgetSelected;
            }
            axios
                .get('/budget/insights-list', {
                    params: {
                        program: selected,
                        channel: channel
                    }
                })
                .then(response => {
                    console.log(response.data);
                    context.commit('budget/SET_BUDGET_NOTES', response.data, { root: true });
                });
        },
        saveBudgetNotes(context, payload) {
            axios
                .post('/budget/insights', { payload })
                .then(response => {
                    let data = response.data;
                    console.log(data);
                    // context.dispatch('workingBudget');
                    context.dispatch('getBudgetNotes');
                    return data;
                });
        },
        getProgramChannelScenario(context, payload) {
            context.commit('loading/SET_LOADING', 'budgetInput', { root: true });
            let filterChannel = context.rootGetters['filter/channel'];
            let budgetChannel = context.rootGetters['budget/channel'];
            let channel = filterChannel;
            if (filterChannel == '') {
                channel = budgetChannel;
                if (_.isString(payload)) {
                    channel = payload;
                }
            }

            let filterSelected = context.rootGetters['filter/selected'];
            let budgetSelected = context.rootGetters['budget/selected'];
            let selected = filterSelected;
            if (filterSelected == '') {
                selected = budgetSelected;
            }

            axios
                .get('/budget/program-channel', {
                    params: {
                        program: selected,
                        channel: channel
                    }
                })
                .then(response => {
                    console.log(response.data);
                    context.commit('budget/SET_PROGRAM_CHANNEL', response.data, { root: true });
                    context.commit('loading/UNSET_LOADING', 'budgetInput', { root: true });
                });
        },

        // Landing Pages
        getLandingPages(context, payload) {
            context.commit('loading/SET_LOADING', 'assets', { root: true });
            axios
                .get('/assets/pages-json', {
                    params: {
                        filter: context.rootGetters['filter/filter'],
                    }
                })
                .then(response => {
                    context.commit('assets/SET_LANDING_PAGES', response.data, { root: true });
                    context.commit('loading/UNSET_LOADING', 'assets', { root: true });
                });
        },
        // Traffic by Landing Page from GA
        getGaPages(context, payload) {
            context.commit('loading/SET_LOADING', 'assets', { root: true });
            context.commit('assets/SET_GA_PAGES', {}, { root: true });
            axios
                .get('/landing-page-json', {
                    params: {
                        program: context.rootGetters['filter/selected'],
                    }
                })
                .then(response => {
                    context.commit('assets/SET_GA_PAGES', response.data, { root: true });
                    context.commit('loading/UNSET_LOADING', 'assets', { root: true });
                });
        },
        // Landing Page Traffic (single page) from GA
        getGaData(context, payload) {
            // context.commit('loading/SET_LOADING', 'assets', { root: true });
            context.commit('assets/SET_GA_DATA', {}, { root: true });
            axios
                .get('/analytics/landing-page-data', {
                    params: {
                        id: payload,
                    }
                })
                .then(response => {
                    context.commit('assets/SET_GA_DATA', response.data, { root: true });
                    // context.commit('loading/UNSET_LOADING', 'assets', { root: true });
                });
        },

        getLaggingOptions(context) {
            axios
                .get('/recruitment/options/lagging')
                .then(response => {
                    context.commit('recruitment/SET_PROGRAMS', response.data.programs, { root: true });
                    context.commit('recruitment/SET_PEOPLE', response.data.people, { root: true });
                });
        },
        getLeadingOptions(context) {
            axios
                .get('/recruitment/options/leading')
                .then(response => {
                    context.commit('recruitment/SET_PROGRAMS', response.data.programs, { root: true });
                    context.commit('recruitment/SET_PEOPLE', response.data.people, { root: true });
                });
        },
        getLaggingData(context, payload) {
            context.commit('loading/SET_LOADING', 'recruitment', { root: true });
            let filter = context.rootGetters['recruitment/dataFilter'];
            filter.range = context.rootGetters['date/rangeArray'];
            axios
                .get('/recruitment/data/lagging', {
                    params: {
                        filter: filter
                    }
                })
                .then(response => {
                    context.commit('recruitment/SET_DATA', response.data.data, { root: true });
                    context.commit('recruitment/SET_PERSON', { role: 'secondmanager', value: response.data.filter.secondmanager }, { root: true });
                    context.commit('recruitment/SET_PERSON', {role: 'admmanager', value: response.data.filter.admmanager}, { root: true });
                    context.commit('recruitment/SET_GROUP', response.data.filter.group, { root: true });
                });
            context.commit('loading/UNSET_LOADING', 'recruitment', { root: true });
        },
        getLeadingData(context, payload) {
            context.commit('loading/SET_LOADING', 'recruitment', { root: true });
            let filter = context.rootGetters['recruitment/dataFilter'];
            filter.range = context.rootGetters['date/rangeArray'];
            axios
                .get('/recruitment/data/leading', {
                    params: {
                        filter: filter
                    }
                })
                .then(response => {
                    // context.commit('recruitment/SET_DATA', response.data, { root: true });
                    context.commit('recruitment/SET_DATA', response.data.data, { root: true });
                    context.commit('recruitment/SET_PERSON', { role: 'secondmanager', value: response.data.filter.secondmanager }, { root: true });
                    context.commit('recruitment/SET_PERSON', { role: 'admmanager', value: response.data.filter.admmanager }, { root: true });
                    context.commit('recruitment/SET_GROUP', response.data.filter.group, { root: true });
                });
            context.commit('loading/UNSET_LOADING', 'recruitment', { root: true });
        },
        getLaggingChart(context, payload) {
            context.commit('loading/SET_LOADING', 'chart', { root: true });
            let filter = context.rootGetters['recruitment/dataFilter'];
            filter.group = 'yearmonth';
            axios
                .get('/recruitment/data/lagging', {
                    params: {
                        filter: filter
                    }
                })
                .then(response => {
                    context.commit('recruitment/SET_CHART_DATA', response.data, { root: true });
                });
            context.commit('loading/UNSET_LOADING', 'chart', { root: true });
        },
        // Download
        downloadData(context, payload) {
            let filter = context.getters.buildFilter(payload);
            axios
                .get('/excel/metrics', {
                    params: {
                        filter: filter
                    }
                })
                .then(response => {
                    console.log(response.data);
                });
        },

        // Map Data
        getMapData(context, payload) {
            let program = context.rootGetters['filter/program'];
            let channel = context.rootGetters['filter/channel'];
            let initiative = context.rootGetters['filter/initiative'];
            let metrics = context.rootGetters['map/metrics'];
            const filter = {
                program,
                channel,
                initiative,
                metrics,
            }

            // Empty Data
            context.commit('map/SET_MAP_DATA', {}, { root: true });

            axios
                .get('/map/geo-data', {
                    params: {
                        filter: filter
                    }
                })
                .then(response => {
                    context.commit('map/SET_MAP_DATA', response.data.pipeline, { root: true });
                });
        },
        getMapCities(context) {
            axios
                .get('/map/city-data')
                .then(response => {
                    context.commit('map/SET_CITIES', response.data, { root: true });
                });
        },

        getDomains(context) {
            axios
                .get('/assets/json')
                .then(response => {
                    context.commit('assets/SET_DOMAINS', response.data, { root: true })
                });
        },

        getSeo2Links(context) {
            axios
                .get('/seo2/json')
                .then(response => {
                    context.commit('assets/SET_LINKS', response.data, { root: true })
                });
        },
        getReferralPages(context) {
            let program = context.rootGetters['filter/selected'];
            axios
                .get('/seo2/referral-traffic', {
                    params: {
                        program
                    }
                })
                .then(response => {
                    context.commit('assets/SET_REFERRAL_PAGES', response.data, { root: true })
                });
        },
        getLinkReport(context, payload) {
            let url = payload;
            axios
                .get('/seo2/link-report', {
                    params: {
                        url
                    }
                })
                .then(response => {
                    context.commit('assets/SET_PAGE_LINKS', response.data, { root: true })
                });
        },

        getDeadlines(context) {
            axios
                .get('/deadlines')
                .then(response => {
                    context.commit('date/SET_DEADLINES', response.data.deadlines, { root: true });
                    context.commit('date/SET_NEXT_DEADLINE', response.data.next, { root: true });
                    context.commit('filter/SET_FILTER_TERM_YEAR', response.data.next.year, { root: true });
                    context.commit('filter/SET_FILTER_SEMESTER', response.data.next.semester, { root: true });
                    console.log('semester: ' + response.data.next.semester);
                });
        },
        getProgramSemesterRevenue(context) {
            let filter = context.rootGetters['filter/filter'];
            axios
                .get('/revenue/program-semester', {
                    params: {
                        filter
                    }
                })
                .then(response => {
                    context.commit('revenue/SET_PROGRAM_SEMESTER', response.data, { root: true })
                });
        },
        getProgramSemesterConversion(context) {
            let filter = context.rootGetters['filter/filter'];
            axios
                .get('/term-conversion', {
                    params: {
                        filter
                    }
                })
                .then(response => {
                    context.commit('conversion/SET_TERM_CONVERSION', response.data, { root: true })
                });
        }
    }
};