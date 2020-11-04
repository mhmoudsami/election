<template>
    <div class="page-inner" id="scroll-target" style="position: relative;">

        <v-overlay :value="isLoading">
            <v-progress-circular indeterminate size="64"></v-progress-circular>
        </v-overlay>


        <h1 class="page-heading">
            المسئولين
        </h1>


        <v-row>

            <v-col
                cols="12"
                sm="6"
                md="6"
            >
                <v-select
                    v-model="city_id"
                    :items="cities"
                    label="المدينه"
                    :loading="isLoading"
                    chips
                    clearable
                    hide-details
                    hide-no-data
                    
                    item-text="name"
                    item-value="id"
                ></v-select>
            </v-col>

            <v-col
                cols="12"
                sm="6"
                md="6"
            >
                <v-select
                    v-model="super_id"
                    :items="list"
                    label="المسئول"
                    :loading="isLoading"
                    chips
                    clearable
                    hide-details
                    hide-no-data
                    
                    item-text="name"
                    item-value="id"
                ></v-select>
            </v-col>
        </v-row>
        

        <!-- table-bordered -->
        <div class="super-item">
            <table class="table supertable table-striped">
                <tbody>
                    <tr class="" style="background: #c5d7e9; color: #44998b">
                        <th>اجمالى المسئولين</th>
                        <td>{{ total }}</td>
                        <th>اجمالى الناخبين</th>
                        <td>{{ candidate_total }}</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <hr>

        <template v-if="(supervisors.length > 0)">
            <template v-for="supervisor in supervisors">

                <!-- table-bordered -->
                <div class="super-item">
                    <a :href="supervisorLink(supervisor.id)" link class="overall-link"></a>
                    <table class="table supertable table-striped">
                        <tbody>
                            <tr>
                                <th>الاسم</th>
                                <td>{{ supervisor.name }}</td>
                                <th>المدينه</th>
                                <td>{{ supervisor.city }}</td>
                                <th>الموبايل</th>
                                <td>{{ supervisor.mobile }} - {{ supervisor.mobile_2 }}</td>
                            </tr>
                            <tr class="otherrow">
                                <th>العنوان</th>
                                <td colspan="3">{{ supervisor.address }}</td>
                                <th><span class="text-green">اجمالى الناخبين</span> </th>
                                <td>{{ supervisor.count }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

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
                list: [],
                city_id: null,
                super_id: null,
                currentPage: null,
                pageCount: 0,
                isLoadingMore:false,
                total: 0,
                candidate_total: 0,
                notify:{
                    display: false,
                    text: '',
                    timeout: 3000,
                    color:'blue'
                },
                cities:[
                    {
                        'name' : 'الخصوص',
                        'id' : 1,
                    },
                    {
                        'name' : 'الخانكه',
                        'id' : 2,
                    },
                    {
                        'name' : 'العبور',
                        'id' : 3,
                    },
                ]
            }
        },
        components: {
            MugenScroll
        },
        created() {
            var status = this.$route.meta.status;
            this.getRequiredData();
            this.initSupervisors();
        },
        computed: {
        },
        watch: {
            $route(to, from) {
            },
            city_id(){
                this.super_id = null;
                this.getRequiredData();
                this.getSupervisors(null , false , true);
                this.getSupervisorsByCity();
            },
            super_id(){
                this.getRequiredData();
                this.getSupervisors(null , false , true);
            },
        },
        methods: {
            getRequiredData(){
                this.isLoading = true;
                Request.defaults.headers.common['Authorization'] = 'Bearer ' + localStorage.getItem('token');
                Request.get('/api/supervisors/data' , {
                    params:{
                        city_id: this.city_id,
                        id: this.super_id,
                    }
                }).then(response => {
                    this.total = response.data.supervisors;
                    this.candidate_total = response.data.candidates;
                    this.isLoading = false;
                });
            },
            supervisorLink(id){
                return "/#/supervisors/"+id;
            },
            initSupervisors(){
                this.getSupervisors();
            },
            getSupervisorsByCity(){
                Request.defaults.headers.common['Authorization'] = 'Bearer ' + localStorage.getItem('token');
                Request.get('/api/supervisors/list' , {
                    params:{
                        city_id: this.city_id,
                        id: this.super_id,
                    }
                }).then(response => {
                    this.list = response.data;
                    this.isLoading = false;
                }).catch(error => {
                });
            },
            getSupervisors(page=null , isLoadingMore=false , clearPast = false){
                this.isLoading = true;
                this.isLoadingMore = isLoadingMore;


                Request.defaults.headers.common['Authorization'] = 'Bearer ' + localStorage.getItem('token');
                Request.get('/api/supervisors' , {
                    params:{
                        page: page,
                        id: this.super_id,
                        city_id: this.city_id,
                    }
                }).then(response => {
                    if ( clearPast ) {
                        this.supervisors = response.data.data;
                    }else{
                        this.supervisors = this.supervisors.concat(response.data.data);
                    }
                    
                    this.pageCount = response.data.last_page;
                    this.currentPage = response.data.current_page;
                    this.total = response.data.total;
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
