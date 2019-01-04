const emptyRecruitmentObject = (state) => {
    let terms = state.terms;
    let stages = state.stages;
    let empty = {};
    _.forEach(terms, term => {
        empty[term] = {};
        _.forEach(stages, stage => {
            empty[term][stage] = 0;
        });
    });
    return empty;
};

const emptyMarketingObject = (state, getters, rootState, rootGetters) => {
    const obj = {}
    const months = rootGetters['selects/lowercaseMonths'];
    obj.leads = {}
    obj.spend = {}
    obj.cpl = {}
    _.forEach(months, mo => {
        obj.leads[mo] = 0;
        obj.spend[mo] = 0;
        obj.cpl[mo] = 0;
    });
    return obj;
};

const hasProgram = state => {
    let pro = (state.selected == '') ? false : true;
    return pro;
};
const hasChannel = state => {
    let ch = (state.selectedChannel == '') ? false : true;
    return ch;
};
const hasInput = state => {
    let input = (Object.getOwnPropertyNames(state.inputObject).length === 0) ? false : true;
    return input;
};

const scenarioTotal = (state, getters, rootState, rootGetters) => (program, channel, scenario, metric) => {
    const months = rootGetters['selects/lowercaseMonths'];
    const scenarios = state.budgetScenarios;
    let sum = 0;
    let array = [];

    // If empty channel, loop through them all
    if (channel == '' || channel == null) {
        let channels = rootGetters['selects/channels'];
        let programSum = 0;
        _.forEach(channels, ch => {
            let channelSum = getters.scenarioTotal(program, ch, scenario, metric);
            programSum += channelSum;
        });
        return programSum;
    } else {
        if (scenarios[program]) {
            if (scenarios[program][channel]) {
                if (scenarios[program][channel][scenario]) {
                    array = scenarios[program][channel][scenario][metric]
                }
            }
        }
        
        _.forEach(months, mo => {
            if (array[mo]) {
                sum += array[mo];
            }
        });
    }
    return sum;
};

const newestScenarioTotal = (state, getters, rootState, rootGetters) => (program, channel, scenario, metric) => {
    let scenarioOrder = ['version3', 'version2', 'version1', 'initial'];
    let channels = rootGetters['selects/channels'];
    let total = 0;
    if (channel == '') {
        _.forEach(channels, ch => {
            let channelTotal = 0
            _.forEach(scenarioOrder, sc => {
                let thisTotal = getters.scenarioTotal(program, ch, sc, metric);
                if (channelTotal == 0) {
                    channelTotal = thisTotal;
                }
            });
            total += channelTotal;
        });
    } else {
        let channelTotal = 0
        _.forEach(scenarioOrder, sc => {
            let thisTotal = getters.scenarioTotal(program, channel, sc, metric);
            if (channelTotal == 0) {
                channelTotal = thisTotal;
            }
        });
        total = channelTotal;
    }
    return total;
};

const inputValue = state => (row, col) => {
    let obj = state.inputObject;
    if (! obj[row] || ! obj[row][col]) {
        return ''
    } else {
        return obj[row][col];
    }
};

const initiativeValue = state => (init, metric, month) => {
    let obj = state.workingInitiatives[init];
    if (!obj) {
        return '';
    }
    if (!obj[metric] || !obj[metric][month]) {
        return ''
    } else {
        return obj[metric][month];
    }
};

const initiativeTotals = (state, getters, rootState, rootGetters) => (init) => {
    let months = rootGetters['selects/lowercaseMonths'];
    let sum = {
        leads: 0,
        spend: 0,
    }

    // if (!state.inputObject[init]) {
    //     return sum;
    // }
    let obj = state.inputObject[init];
    if (!obj) {
        return sum;
    }
    if (obj.hasOwnProperty('leads')) {
        _.forEach(months, mo => {
            let leads = 0;
            if (obj['leads'][mo]) {
                leads = parseInt(obj['leads'][mo]);
            }

            sum.leads += (leads) ? leads : 0;
        });
    }

    return sum;
};

const actualStageRates = (state, getters, rootState, rootGetters) => (type) => {
    let actuals = {}
    switch (type) {
        case 'programTotal':
            actuals = state.actualsProgramTotal[state.selected];
            break;
        case 'channelVertical':
            actuals = state.actualsChannelVertical[state.selectedVertical];
            break;
        case 'programChannel':
            actuals = state.actuals[state.selected];
            break;
        default:
            break;
    }
    
    let stages = state.stages;

    if (! actuals) {
        return null;
    }

    let range = rootGetters['date/range'];
    let yearMonths = rootGetters['date/rangeYearMonth'](range.start, range.end);
    
    let stageArray = [{
        stage: 'leads',
        value: 0,
        rate: 0
    }];

    _.forEach(yearMonths, mo => {
        if (actuals && actuals['leads'] && actuals['leads'][mo]) {
            stageArray[0].value += actuals['leads'][mo];
        }
    });

    let prevTotal = stageArray[0].value;

    _.forEach(stages, (stage, index) => {
        let sum = 0;
        let array = actuals[stage];
        _.forEach(yearMonths, mo => {
            if (array && array[mo]) {
                sum += array[mo];
            }
        });

        let rate = sum / prevTotal;

        let stageElement = {
            stage: stage,
            value: sum,
            rate: rate
        };
        
        stageArray.push(stageElement);
        prevTotal = sum;
    });

    return stageArray;
};

// Check for items in working budget
const programHasBudget = (state) => (program) => {
    let budget = state.workingBudget;
    let filteredBudget = _.filter(budget,{ 'program': program });
    if (filteredBudget.length > 0) {
        return true;
    } else {
        return false;
    }
};

const programHasScenario = (state) => (program) => {
    let budget = state.budgetScenarios;
    if (!budget['program']) {
        return false;
    }
    let filteredBudget = budget['program'];
    let channels = rootGetters['selects/channels'];
    let scenario = state.scenario;
    let hasScenario = 0;

    _.forEach(channels, ch => {
        let string = channel + '.' + scenario;
        if (!budget['program'][ch][scenario]) {
            //
        } else {
            hasScenario++;
        }
    });
    if (hasScenario > 0) {
        return true;
    } else {
        return false;
    }
};

const programsWithScenario = (state, getters, rootState, rootGetters) => {
    let channel = rootGetters['filter/channel'];
    let channels = rootGetters['selects/channels'];
    let scenario = state.scenario;
    let budget = state.budgetScenarios;
    let programArray = [];
    _.forEach(budget, (b, key) => {
        if (channel == '') {
            _.forEach(channels, ch => {
                if (_.has(b, [ch, scenario])) {
                    programArray.push(key);
                }
            });
        } else {
            if (_.has(b, [channel, scenario])) {
                programArray.push(key);
            }
        }
    });
    return programArray;
};
const programsWithAnyScenario = (state, getters, rootState, rootGetters) => {
    let channel = rootGetters['filter/channel'];
    let channels = rootGetters['selects/channels'];
    let scenario = state.scenario;
    let list = ['version2', 'version1', 'initial'];
    let budget = state.budgetScenarios;
    let programArray = [];
    _.forEach(budget, (b, key) => {
        if (channel == '') {
            _.forEach(channels, ch => {
                _.forEach(list, item => {
                    if (_.has(b, [ch, item])) {
                        programArray.push(key);
                    }
                });
            });
        } else {
            _.forEach(list, item => {
                if (_.has(b, [channel, item])) {
                    programArray.push(key);
                }
            });
        }
    });
    return programArray;
};

const programChannelScenario = (state, getters, rootState, rootGetters) => {
    let filterChannel = rootGetters['filter/channel'];
    let budgetChannel = getters.channel;
    let channel = budgetChannel;
    if (channel == '') {
        channel = filterChannel;
    }
    let filterProgram = rootGetters['filter/selected'];
    let budgetProgram = getters.program;
    let program = budgetProgram;
    if (program == '') {
        program = filterProgram;
    }
    // If no program & channel selected, return nothing
    if (program == '' || channel == '') {
        return '';
    }

};

const programChannelHasBudget = (state) => (program, channel) => {
    let budget = state.workingBudget;
    let filteredBudget = _.filter(budget, { 'program': program, 'channel': channel });
    if (filteredBudget.length > 0) {
        return true;
    } else {
        return false;
    }
};

const programChannelBudget = (state, getters) => (program, channel) => {
    let stages = state.stages;
    if (getters.programChannelHasBudget(program, channel)) {
        let budget = state.workingBudget;
        let filtered = _.filter(budget, { 'program': program, 'channel': channel });
        
        return filtered;
    } else {
        return null;
    }
};

const workingInitiativeTotals = (state, getters, rootState, rootGetters) => init => {
    let working = state.workingInitiatives[init];
    return working;
}

export default {

};