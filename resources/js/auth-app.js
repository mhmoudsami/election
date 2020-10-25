import Vue from 'vue';
import Request from 'axios';
import VueRouter from 'vue-router'
import vuetify from './plugins/vuetify';

/**   pages
*********************/
import AuthApp from '../views/auth/AuthApp';
import LoginPage from '../views/auth/Login';

/**   set language header
*********************/
Request.defaults.headers.common['Accept-Language'] = 'ar';

Vue.use(VueRouter);


/**   define routes
*********************/
const router = new VueRouter({
    routes: [
        {
            path: "/",
            redirect: {
                name: "login"
            }
        },
        {
            path: '/login',
            name: 'login',
            component: LoginPage,
            meta : {
                title : 'تسجيل الدخول',
            }
        },
        { 
            path: '*', 
            redirect: {
                name: "login"
            }
        }
    ],
    // instead of hashes
    // mode: 'history'
});

/**   This callback runs before every route change, including on page load.
*********************/
router.beforeEach((to, from, next) => {
    document.title = "Election - " + to.meta.title;
    next();
});


var token = localStorage.getItem('token');

/**   init vue app
*********************/
if ( token ) {
    window.location.replace('/');
}else{
    new Vue({
    	el: '#auth-app',
        router,
        vuetify,
        render: h => h(AuthApp)
    });
}