<template>
    <div class="page-inner" id="scroll-target">

        <v-overlay :value="isLoading">
            <v-progress-circular indeterminate size="64"></v-progress-circular>
        </v-overlay>



        <h1 class="page-heading hidden">
            الناخبين
        </h1>


        <template v-if="isSupervisor != 'false'">
            <v-text-field
                v-model="candidateName"
                label="اسم الناخب"
                autofocus
                required
            ></v-text-field>
        </template>

        <template v-if="isSupervisor == 'false'">
            <v-row>
                <v-col
                    cols="12"
                    sm="6"
                    md="6"
                >

                    <v-autocomplete
                        v-model="supervisor"
                        :items="supervisors"
                        :loading="isLoading"
                        chips
                        clearable
                        hide-details
                        hide-no-data
                        
                        item-text="name"
                        item-value="id"
                        label="المسئول"
                        :persistent-hint="false"
                    >
                        <template v-slot:selection="{ attr, on, item, selected }">
                            <v-chip
                                v-bind="attr"
                                :input-value="selected"
                                color="blue-grey"
                                class="white--text"
                                v-on="on"
                            >
                                <span v-text="item.name"></span>
                            </v-chip>
                        </template>

                        <template v-slot:no-data>
                            <v-list-item>
                                <v-list-item-title>
                                    جارى البحث عن
                                </v-list-item-title>
                            </v-list-item>
                        </template>

                        <template v-slot:item="{ item }">
                                <v-list-item-content>
                                    <v-list-item-title v-text="item.name"></v-list-item-title>
                                </v-list-item-content>
                            </v-list-item>
                        </template>

                    </v-autocomplete>

                </v-col>
                <v-col
                    cols="12"
                    sm="6"
                    md="6"
                >
                    <div class="" style="margin-top: 14px;">
                        <v-text-field
                            v-model="candidateName"
                            label="اسم الناخب"
                            autofocus
                            required
                        ></v-text-field>
                    </div>
                </v-col>
            </v-row>
        </template>



        <v-divider :class="'my-5'"></v-divider>

        
        <template v-if="(candidates.length > 0)">
            <template v-for="candidate in candidates">
                <v-card
                    class="mx-auto order-item"
                    tile
                    link
                    :href="candidateLink(candidate.id)"
                >
                    <v-list-item>
                        <v-list-item-content>
                            <!-- <v-list-item-title>Single order item</v-list-item-title> -->
                            <div class="order-item-columns d-flex justify-space-between">
                                <div class="order-item-col">
                                    <ul>
                                        <li>
                                            <v-chip
                                                class=""
                                            >
                                                {{ candidate.uid }}
                                            </v-chip>
                                        </li>
                                        <li>
                                            {{ candidate.name }}
                                        </li>
                                        <li>
                                            
                                        </li>
                                    </ul>
                                </div>
                                <div class="order-item-col">
                                    <ul>
                                        <li>
                                            <b>المركز الانتخابى</b> : {{ candidate.location }}
                                        </li>
                                        <li>
                                            <b>العنوان</b> : {{ candidate.address }}
                                        </li>
                                        <li>
                                            <b>القسم</b> : {{ candidate.police }}
                                        </li>
                                    </ul>
                                </div>
                                <div class="order-item-col">
                                    <ul>
                                        <li>
                                            اللجنة الفرعية : {{ candidate.boxnumber }}
                                        </li>
                                        <li>
                                            رقم الكشف : {{ candidate.citizen_number }}
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
        name: 'candidates',
        data () {
            return {
                isSupervisor:false,
                super_id:false,
                isLoading:false,
                candidates: [],
                currentPage: null,
                pageCount: 0,
                isLoadingMore:false,
                candidateName: '',

                supervisor:null,
                supervisorSync:null,
                supervisors:[],
            }
        },
        components: {
            MugenScroll
        },
        created() {
            this.isSupervisor = localStorage.getItem('isSupervisor');
            this.super_id = localStorage.getItem('super_id');

            this.getAppData();
            this.initCandidates();
        },
        computed: {
        },
        watch: {
            $route(to, from) {
            },
            supervisor(){
                this.getCandidates(null , false , true);
            },
            candidateName(){
                this.getCandidates(null , false , true);
            }
        },
        methods: {
            getAppData(){
                this.isLoading = true;
                Request.defaults.headers.common['Authorization'] = 'Bearer ' + localStorage.getItem('token');
                Request.get('/api/supervisors/list',).then(response => {
                    this.supervisors = response.data;
                    this.isLoading = false;
                });
            },
            candidateLink(id){
                return "/#/candidates/"+id;
            },
            initCandidates(){
                this.getCandidates();
            },
            getCandidates(page=null , isLoadingMore=false , clearPast = false){
                this.isLoading = true;
                this.isLoadingMore = isLoadingMore;


                Request.defaults.headers.common['Authorization'] = 'Bearer ' + localStorage.getItem('token');
                Request.get('/api/candidates' , {
                    params:{
                        page: page,
                        supervisor: this.supervisor,
                        candidateName: this.candidateName,
                        super_id: this.super_id,
                    }
                }).then(response => {
                    if ( clearPast ) {
                        this.candidates = response.data.data;
                    }else{
                        this.candidates = this.candidates.concat(response.data.data);
                    }
                    
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
                this.getCandidates((this.currentPage+=1) , true);
            },
        }
    }
</script>
