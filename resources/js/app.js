import { createApp } from 'vue';
import store  from './store/index.js';
import router  from './router';



import exampleComponent from './components/ExampleComponent.vue';
const app = createApp({});
app.use(store);
app.use(router);

//register the component
app.component('example-component', exampleComponent);

//..HTML element to mount the Vue application
app.mount('#app');

require('./bootstrap');
