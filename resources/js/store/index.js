import Vue from 'vue';
import Vuex from 'vuex';

Vue.use(Vuex);

import assets from './modules/assets';
import attributes from './modules/attributes';
import budget from './modules/budget';
import page from './modules/page';
import user from './modules/user';

export default new Vuex.Store ({
    modules: {
        assets,
        attributes,
        budget,
        page,
        user
    }
});
