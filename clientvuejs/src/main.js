import { createApp } from 'vue'
import App from './App.vue'
// import UserComp from './components/User'
import axios from 'axios'
import VueAxios from 'vue-axios'  
import router from './router/routes' 

import 'bootstrap/dist/css/bootstrap.min.css'
import 'jquery/src/jquery.js'
import 'bootstrap/dist/js/bootstrap.min.js'
import Paginate from "vuejs-paginate-next";

import storeConfigs  from './store/store'

import { quillEditor } from "vue3-quill";
// console.log(quillEditor);

import gAuthPlugin from 'vue3-google-oauth2';
let gauthClientId = "779842371071-gn56d5d8o7l8blerqn3nb75v3vnhsbqf.apps.googleusercontent.com";

import Notifications from "vue3-vt-notifications";
import "tailwindcss/dist/tailwind.css";

const app = createApp(App);
// const app = createApp(UserComp);


app.use(gAuthPlugin, {
    clientId: gauthClientId,
    scope: 'email',
    prompt: 'consent',
  })

app.use(router);
app.use(VueAxios, axios)
app.use(storeConfigs)
app.use(Paginate)
app.use( quillEditor )
app.use( Notifications )
app.mount('#app')