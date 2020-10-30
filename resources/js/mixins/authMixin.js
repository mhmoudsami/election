import Request from 'axios';

export default {
    data: function(){
        return {
            isLoading: false,
            appData:{
                drivers: [],
                branchs: [],
                payments: [],
            },
        }
    },
    components: {
    },
    created() {
    },
    methods: {
        logAgentIn(login, pwd)
        {
            this.openLoader();
            Request.defaults.headers.common['Authorization'] = 'Bearer ' + localStorage.getItem('token');

            Request.post('/api/login', {
                name: login,
                password: pwd,
            }).then(response => {
                console.log(response.data);
                if (response.data.success) {
                    localStorage.setItem('token' , response.data.token);
                    localStorage.setItem('isSupervisor' , response.data.isSupervisor);
                    localStorage.setItem('super_id' , response.data.super_id);
                    window.location.replace('/');
                }
                this.closeLoader();
                return false;
            });
        },
    }
}