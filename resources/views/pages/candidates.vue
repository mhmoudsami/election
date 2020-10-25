<template>
    <div class="page-inner" id="scroll-target">

        <v-overlay :value="isLoading">
            <v-progress-circular indeterminate size="64"></v-progress-circular>
        </v-overlay>



        <h1 class="page-heading">
            الناخبين
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
                isLoading:false,
                candidates: [],
                currentPage: null,
                pageCount: 0,
                isLoadingMore:false,
            }
        },
        components: {
            MugenScroll
        },
        created() {
            var status = this.$route.meta.status;
            this.selectedTab = '/orders/'+status;
            this.initCandidates();
            // console.log(this.$route);
        },
        computed: {
        },
        watch: {
            $route(to, from) {
                var new_status = to.meta.status;
                this.selectedTab = '/orders/'+new_status;
                // this.getOrders(new_status);
            }
        },
        methods: {
            candidateLink(id){
                return "/#/candidates/"+id;
            },
            initCandidates(){
                this.getCandidates();
            },
            getCandidates(page=null , isLoadingMore=false){
                this.isLoading = true;
                this.isLoadingMore = isLoadingMore;

                Request.defaults.headers.common['Authorization'] = 'Bearer ' + localStorage.getItem('token');
                Request.get('/api/candidates' , {
                    params:{
                        page: this.currentPage,
                    }
                }).then(response => {
                    this.candidates = this.candidates.concat(response.data.data);
                    this.pageCount = response.data.total;
                    this.currentPage = response.data.current_page;
                    console.log(response.data);
                    this.isLoading = false;
                }).catch(error => {
                    this.notify.display = true;
                    this.notify.text = 'حدث خطأ ما , رجاء اعادة المحاوله';
                    this.isLoading = false;
                });
            },
            loadMore(){
                this.getCandidates(this.currentPage++ , true);
            },
        }
    }
</script>
