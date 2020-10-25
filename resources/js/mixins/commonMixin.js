import Request from 'axios';


export default {
    data: function(){
        return {
            isLoading: false,
            appData:{
            },
        }
    },
    components: {
    },
    created() {
    },
    methods: {
        goTo(name){
            if ( this.$route.name == name ) {
                return false;
            }
            this.$router.push({ name: name});
        },
        openLoader(){
            this.isLoading = true;
        },
        closeLoader(){
            this.isLoading = false;
        }
    }
}