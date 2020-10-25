import Request from 'axios';
import MugenScroll from 'vue-mugen-scroll';


export default {
    data: function(){
        return {
            isLoading: false,
            isLoadingMore: false,
            appData:{
                payment_methods: [],
                restaurant: {},
                restaurant_branchs: [],
                branch: {},
                areas: [],
            },
        }
    },
    components: {
        MugenScroll
    },
    created() {
    },
    methods: {
        getOrders(status)
        {
            this.isLoading = true;
            // this.orders.status = status;
            Request.defaults.headers.common['Authorization'] = 'Bearer ' + localStorage.getItem('token');
            Request.get('/api/v1/go/orders/'+status).then(response => {
                this.orders = response.data.data;
                this.currentPage = response.data._meta.currentPage;
                this.pageCount = response.data._meta.pageCount;
                this.isLoading = false;
                if ( status == 'waiting' ) {
                    if ( this.orders.length > 0 ) {
                        this.lastId = this.orders[0].id;
                        console.log('yes');
                        console.log(this.lastId);
                    }
                    this.updateOrders('waiting');
                }
            }).catch(error => {
                if ( error.response.status == 401 ) {
                    return window.location.replace('/go/auth/logout');
                }
            });
        },
        updateOrders(status)
        {
            if ( this.$route.name != 'orders' ) {
                return false;
            }

            console.log('hello');

            Request.defaults.headers.common['Authorization'] = 'Bearer ' + localStorage.getItem('token');
            Request.get('/api/v1/go/orders/'+status).then(response => {
                this.orders = response.data.data;
                this.currentPage = response.data._meta.currentPage;
                this.pageCount = response.data._meta.pageCount;

                // notify
                if (this.orders.length > 0 && this.orders[0].id == this.lastId) {
                    this.notify('New Order!', 'Click to view the new orders.');
                    this.lastId = this.orders[0].id;
                }
            });

            // try agin every 10 seconds
            var self = this;
            setTimeout(function(){
                return self.updateOrders(status);
            }, 10000);
        },
        // load more orders
        loadMore()
        {
            var status = this.$route.meta.status;

            this.isLoadingMore = true;
            Request.defaults.headers.common['Authorization'] = 'Bearer ' + localStorage.getItem('token');
            Request.get('/api/v1/go/orders/'+status+'?page='+(this.currentPage+1)).then(response => {
                this.orders = this.orders.concat(response.data.data);
                this.currentPage = response.data._meta.currentPage;
                this.pageCount = response.data._meta.pageCount;
                this.isLoadingMore = false;
            });
        },
        getOrderDetails(id)
        {
            this.isLoading = true;
            Request.defaults.headers.common['Authorization'] = 'Bearer ' + localStorage.getItem('token');
            Request.get('/api/v1/go/orders/view' , {
                params: {
                    id : id,
                }
            }).then(response => {
                this.order = response.data.data;
                this.isLoading = false;
            });
        },

        getProfileData(){
            this.isLoading = true;
            Request.defaults.headers.common['Authorization'] = 'Bearer ' + localStorage.getItem('token');
            Request.get('/api/v1/go/profile/branch')
            .then(response => {
                if ( response.data.success ) {
                    this.currentBranch = response.data.data;
                }
                this.isLoading = false;
            });
        },

        getAppData(){
            this.isLoading = true;
            Request.defaults.headers.common['Authorization'] = 'Bearer ' + localStorage.getItem('token');
            Request.get('/api/v1/go/app/index').then(response => {
                // console.log(response.data);
                this.appData = response.data;
                this.isLoading = false;
            });
        },
        mapChoose(){

        },


        notify(title, body)
        {
            if (!Notification) {
                return;
            }

            if (Notification.permission !== "granted"){
                Notification.requestPermission();
            }

            var notification = new Notification(title, {
                icon: $('#go-main-app').data('notify-icon'),
                body: body,
            });

            notification.onclick = function () {
                window.open($('#go-main-app').data('notify-url'));
            };
        }
    }
}