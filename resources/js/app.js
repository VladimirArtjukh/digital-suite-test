import './bootstrap';

import {createApp} from "vue/dist/vue.esm-bundler.js";
import TasksComponent from "./components/TasksComponent.vue"
import LoginComponent from "./components/LoginComponent.vue"
import RegisterComponent from "./components/RegisterComponent.vue"

const app = createApp({
    components:{
        TasksComponent,
        LoginComponent,
        RegisterComponent
    }
});
app.mount("#app");
