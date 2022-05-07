import {createRouter, createWebHashHistory} from 'vue-router';

import OperatorLogin from "./pages/OperatorLogin.vue";
import Home from "./pages/Home.vue";

const routes = [
    { path: '/', component: Home },
    { path: '/login', component: OperatorLogin },
  ]

const router = createRouter({
    // 4. Provide the history implementation to use. We are using the hash history for simplicity here.
    history: createWebHashHistory(),
    routes, // short for `routes: routes`,
});

export default router;