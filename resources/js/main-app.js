import Vue from 'vue';
import Request from 'axios';
import VueRouter from 'vue-router'
import vuetify from './plugins/vuetify';




/**   mixins
*********************/
import commonMixin from './mixins/commonMixin';
import ordersMixin from './mixins/ordersMixin';


/**   pages
*********************/
import App from '../views/pages/app';
import Home from '../views/pages/home';
import Candidates from '../views/pages/candidates';
import CandidateDetails from '../views/pages/candidate-details';

import Supervisors from '../views/pages/supervisors';
import SupervisorDetails from '../views/pages/supervisor-details';
import SupervisorCreate from '../views/pages/supervisor-create';



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
                name: "home"
            }
        },
        {
            path: '/home',
            name: 'home',
            component: Home,
            meta : {
                title : 'الرئيسيه',
            }
        },
        {
            path: '/candidates',
            name: 'candidates',
            component: Candidates,
            meta : {
                title : 'كل الناخبين',
            }
        },
        {
            path: '/candidates/:id',
            name: 'candidate-details',
            component: CandidateDetails,
            meta : {
                title : 'تفاصيل الناخب',
            }
        }, 
        {
            path: '/supervisors',
            name: 'supervisors',
            component: Supervisors,
            meta : {
                title : 'المسئولين',
            }
        },
        {
            path: '/supervisors/create',
            name: 'supervisor-create',
            component: SupervisorCreate,
            meta : {
                title : 'اضافة مسئول جديد',
            }
        },
        {
            path: '/supervisors/:id',
            name: 'supervisor-details',
            component: SupervisorDetails,
            meta : {
                title : 'تفاصيل المسئول',
            }
        },  
        { 
            path: '*', 
            redirect: {
                name: "orders"
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



/**   Global FIlter.
*********************/
Vue.filter('momentDiff', function (value) {
    value = moment.unix(value);
    let now = moment(now);

    return now.to(value);
});

var token = localStorage.getItem('token');

/**   init vue app
*********************/
if ( ! token ) {
    window.location.replace('/auth/');
}else{
    new Vue({
        el: '#main-app',
        router,
        vuetify,
        render: h => h(App),
    });
}