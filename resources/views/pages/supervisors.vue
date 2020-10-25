<template>
    <div class="page-inner" id="scroll-target" style="position: relative;">

        <v-overlay :value="isLoading">
            <v-progress-circular indeterminate size="64"></v-progress-circular>
        </v-overlay>


        <h1 class="page-heading">
            المسئولين
        </h1>


        <v-tabs-items>
            <v-tab-item
            >
                <v-card
                    class="mx-auto order-item"
                    tile
                >
                    <v-list-item>
                        <v-list-item-content>
                            <v-list-item-title>Single order item</v-list-item-title>
                        </v-list-item-content>
                    </v-list-item>
                </v-card>

            </v-tab-item>
        </v-tabs-items>
        
        <template v-if="(supervisors.length > 0)">
            <template v-for="supervisor in supervisors">
                <v-card
                    class="mx-auto order-item"
                    tile
                    link
                    :href="supervisorLink(supervisor.id)"
                >
                    <v-list-item>
                        <v-list-item-content>
                            <!-- <v-list-item-title>Single order item</v-list-item-title> -->
                            <div class="order-item-columns d-flex justify-space-between">
                                <div class="order-item-col">
                                    <ul>
                                        <li>
                                            {{ supervisor.name }}
                                        </li>
                                        <li>
                                            {{ supervisor.mobile }}
                                        </li>
                                        <li>
                                            
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </v-list-item-content>
                    </v-list-item>
                </v-card>
            </template>
        </template>

        <template v-else>
            <v-alert
                dense
                type="info"
            >
                لا يوجد
            </v-alert>
        </template>

        
        
        <mugen-scroll :handler="loadMore" :should-handle="!isLoadingMore && currentPage < pageCount">
            <template v-if="!isLoadingMore && currentPage < pageCount">
                جارى تحميل المزيد
            </template>
        </mugen-scroll>

    </div>
</template>

<script>
    import Request from 'axios';
    import MugenScroll from 'vue-mugen-scroll';

    export default {
        name: 'supervisors',
        data () {
            return {
                isLoading:false,
                supervisors: [],
                currentPage: null,
                pageCount: 0,
                isLoadingMore:false,
                notify:{
                    display: false,
                    text: '',
                    timeout: 3000,
                    color:'blue'
                }
            }
        },
        components: {
            MugenScroll
        },
        created() {
            var status = this.$route.meta.status;
            this.initSupervisors();
        },
        computed: {
        },
        watch: {
            $route(to, from) {
            }
        },
        methods: {
            supervisorLink(id){
                return "/#/supervisors/"+id;
            },
            initSupervisors(){
                this.getSupervisors();
            },
            getSupervisors(page=null , isLoadingMore=false){
                this.isLoading = true;
                this.isLoadingMore = isLoadingMore;

                if ( isLoadingMore ) {
                    console.log('dfdfdf');
                }

                Request.defaults.headers.common['Authorization'] = 'Bearer ' + localStorage.getItem('token');
                Request.get('/api/supervisors' , {
                    params:{
                        page: page,
                    }
                }).then(response => {
                    this.supervisors = this.supervisors.concat(response.data.data);
                    this.pageCount = response.data.last_page;
                    this.currentPage = response.data.current_page;
                    // console.log(response.data);
                    this.isLoading = false;
                    this.isLoadingMore = false;
                }).catch(error => {
                    this.notify.display = true;
                    this.notify.text = 'حدث خطأ ما , رجاء اعادة المحاوله';
                    this.isLoading = false;
                    this.isLoadingMore = false;
                });
            },
            loadMore(){
                this.getSupervisors((this.currentPage+=1) , true);
            },
        }
    }
</script>
