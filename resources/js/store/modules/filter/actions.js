import axios from 'axios';

const clearFilter = (context) => {
    context.commit('SET_SELECTED', '');
};

const clearFilters = (context, payload) => {
  context.commit('SET_FILTER_LOCATION', '');
  context.commit('SET_FILTER_BU', '');
  context.commit('SET_FILTER_PARTNER', '');
};

const setSelected = (context, payload) => {
  context.commit('SET_SELECTED', payload);
  context.commit('SET_EXPAND_SELECTED', '');
  
  context.dispatch('table/updateSelected', {}, { root: true });

  // context.dispatch('json/getNotes', {}, { root: true });
  // // context.dispatch('json/getActionItems', {}, { root: true });
  // context.dispatch('json/getRevenueData', {}, { root: true });
};

const setSelectedGroup = (context, payload) => {
  context.commit('SET_SELECTED_GROUP', payload);
};

const setExpandType = (context, payload) => {
  context.commit('SET_EXPAND_TYPE', payload);
};

const setExpandSelected = (context, payload) => {
  if (payload.expandType == 'channel') {
      context.commit('SET_EXPAND_SELECTED', payload.channel);
      context.commit('SET_EXPAND_TYPE', payload.expandType);
      context.commit('table/SET_TABLE_FILTER_GROUP', payload.expandType, { root: true });
  }
  if (payload.channel != '') {
      
  }
  context.dispatch('json/getTableData', payload, { root: true });
};

const checkData = (context) => {
  context.commit('SET_SELECTED', '');
  context.commit('SET_EXPAND_SELECTED', '');
  context.commit('table/SET_TABLE_FILTER_GROUP', context.getters.group, { root: true });
  context.dispatch('metrics/refreshData', null, { root: true });
};

const checkTermData = (context) => {
  context.dispatch('metrics/refreshTermData', null, { root: true });
};

const setFilterGroup = (context, payload) => {
  context.commit('SET_FILTER_GROUP', payload);
  context.commit('SET_SELECTED_GROUP', payload);
  context.dispatch('checkData');
};

const setFilterExcludeGroup = (context, payload) => {
  context.commit('SET_FILTER_EXCLUDE_GROUP', payload);
  context.commit('SET_FILTER_EXCLUDE', []);
  // context.dispatch('checkData');
};

const setFilterExclude = (context, payload) => {
  context.commit('SET_FILTER_EXCLUDE', payload);
  context.dispatch('checkData');
};

const setFilterExcludeChannels = (context, payload) => {
  context.commit('SET_FILTER_EXCLUDE_CHANNELS', payload);
  context.dispatch('checkData');
};

const setFilterPartner = (context, payload) => {
  context.commit('SET_FILTER_PARTNER', payload);
  context.dispatch('checkData');
};

const setFilterProgram = (context, payload) => {
  context.commit('SET_FILTER_PROGRAM', payload);
  context.dispatch('checkData');
};

const setFilterLocation = (context, payload) => {
  context.commit('SET_FILTER_LOCATION', payload);
  context.dispatch('checkData');
};

const setFilterBu = (context, payload) => {
  context.commit('SET_FILTER_BU', payload);
  context.dispatch('checkData');
};

const setFilterVertical = (context, payload) => {
  context.commit('SET_FILTER_VERTICAL', payload);
  context.dispatch('checkData');
};

const setFilterSubvertical = (context, payload) => {
  context.commit('SET_FILTER_SUBVERTICAL', payload);
  context.dispatch('checkData');
};

const setFilterChannel = (context, payload) => {
  context.commit('SET_FILTER_CHANNEL', payload);
  context.dispatch('checkData');
};

const setFilterInitiative = (context, payload) => {
  context.commit('SET_FILTER_INITIATIVE', payload);
  context.dispatch('checkData');
};

const setFilterList = (context, payload) => {
  context.commit('SET_FILTER_LIST', payload);
  context.dispatch('checkData');
};

const setFilterTermYear = (context, payload) => {
  context.commit('SET_FILTER_TERM_YEAR', payload);
  context.dispatch('checkTermData');
};

const setFilterVintage = (context, payload) => {
  context.commit('SET_FILTER_VINTAGE', payload);
  context.dispatch('checkData');
};

const setFilterLevel = (context, payload) => {
  context.commit('SET_FILTER_LEVEL', payload);
  context.dispatch('checkData');
};

const setFilterType = (context, payload) => {
  context.commit('SET_FILTER_TYPE', payload);
  context.dispatch('checkData');
};

const setFilterSemester = (context, payload) => {
  if (payload == null) {
      payload = '';
  }
  context.commit('SET_FILTER_SEMESTER', payload);
  context.dispatch('checkTermData');
};

const setFilterTerm = (context, payload) => {
  context.commit('SET_FILTER_TERM', payload);
  context.dispatch('checkTermData');
};

const setFilterIcon = (context) => {
  let ch = this.filter.channel;
  let ic = this.icons.ch.icon;
  context.commit('SET_FILTER_ICON', ic);
};

const setFilterQuery = (context, payload) => {
  context.commit('SET_FILTER_QUERY', payload);
};

const setFilterSort = (context, payload) => {
  context.commit('SET_FILTER_SORT', payload);
};

const setFilterOrder = (context, payload) => {
  let sort = payload.sort;
  let order = payload.order;
  context.commit('SET_FILTER_SORT', sort);
  context.commit('SET_FILTER_ORDER', order);
};

const setFilterStrategy = (context, payload) => {
  context.commit('SET_FILTER_STRATEGY', payload);
  context.dispatch('checkData');
};

const setFilterStarbucks = (context, payload) => {
  context.commit('SET_FILTER_STARBUCKS', payload);
  context.dispatch('checkData');
};

const swapFilterOrder = (context) => {
  let order = context.getters.order;
  if (order == 'asc') {
      context.commit('SET_FILTER_ORDER', 'desc');
  } else {
      context.commit('SET_FILTER_ORDER', 'asc');
  }
};

const setBudgetType = (context, payload) => {
  context.commit('SET_BUDGET_TYPE', payload);
  context.dispatch('checkData');
};

const setFilterMine = (context, payload) => {
  context.commit('SET_FILTER_MINE', payload);
};

const toggleFilterMine = (context, payload) => {
  let mine = context.getters.useMine;
  // Currently false, will be true, set my programs
  if (mine == false) {
      let myPrograms = context.rootGetters['user/myPrograms'];
      context.dispatch('setFilterList', myPrograms);
  } else {
      context.dispatch('setFilterList', []);
  }
  context.commit('TOGGLE_FILTER_MINE', payload);
};

const filterMyBu = (context) => {
  let myBu = context.rootGetters['user/myBu'];
  context.commit('SET_FILTER_BU', myBu);
}

export default {
  clearFilter,
};