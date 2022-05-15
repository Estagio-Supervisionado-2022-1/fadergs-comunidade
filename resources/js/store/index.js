import { createStore } from 'vuex';

import operatorModule from "./Operator";

const store = createStore({
    modules: {
			operator:{
				namespaced: true,
				...operatorModule
			}
    }
});


export default store;