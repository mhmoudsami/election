<template>
    <v-app id="inspire">

        <v-navigation-drawer
            v-model="drawer"
            :clipped="$vuetify.breakpoint.lgAndUp"
            app
            right
        >
            <v-list dense class="font-weight-bold">
                <template v-for="item in items">
                    <v-list-item
                        :key="item.text"
                        link
                        @click="goTo(item.link)"
                    >
                        <v-list-item-action>
                            <v-icon>{{ item.icon }}</v-icon>
                        </v-list-item-action>
                        <v-list-item-content>
                            <v-list-item-title>
                                {{ item.text }}
                            </v-list-item-title>
                        </v-list-item-content>
                    </v-list-item>
                </template>
            </v-list>
        </v-navigation-drawer>

        <v-app-bar
            :clipped-right="$vuetify.breakpoint.lgAndUp"
            app
            color="purple"
            dark
        >
            <v-app-bar-nav-icon @click.stop="drawer = !drawer" />

            <v-toolbar-title
                style="width: 300px"
                class="ml-0 pl-4"
                href="#"
            >
                <span class="hidden-sm-and-down">
                    <!-- <v-avatar>
                        <v-img :src="appData.restaurant.logo"></v-img>
                    </v-avatar> -->
                    Election
                </span>
            </v-toolbar-title>

            <div class="hidden">
            <v-autocomplete
                v-model="searchTerm"
                :items="candidatesResult"
                :loading="isLoading"
                :search-input.sync="CandidateSearch"
                chips
                clearable
                hide-details
                hide-no-data
                
                item-text="id"
                item-value="id"
                label="البحث بالرقم القومى"
                solo-inverted
                :persistent-hint="false"
            >

                <template v-slot:no-data>
                    <v-list-item>
                        <v-list-item-title>
                            جارى البحث 
                        </v-list-item-title>
                    </v-list-item>
                </template>


                <template v-slot:selection="{ attr, on, item, selected }">
                    <v-chip
                        v-bind="attr"
                        :input-value="selected"
                        color="blue-grey"
                        class="white--text"
                        v-on="on"
                    >
                        <v-icon left>mdi-coin</v-icon>
                        <span v-text="item.id"></span>
                    </v-chip>
                </template>

                <template v-slot:item="{ item }">
                    <v-list-item @click="candidateLink(item.id)">
                        <v-list-item-avatar
                            color="indigo"
                            class="headline font-weight-light white--text"
                        >
                            O
                        </v-list-item-avatar>

                        <v-list-item-content>
                            <v-list-item-title v-text="item.name"></v-list-item-title>
                            <v-list-item-subtitle v-text="item.uid"></v-list-item-subtitle>
                        </v-list-item-content>

                        <v-list-item-action>
                            {{ item.id }}
                        </v-list-item-action>
                    </v-list-item>
                </template>

            </v-autocomplete>
            </div>

            <v-spacer />

            <v-btn icon @click="goTo('home')">
                <v-icon>mdi-plus</v-icon>
            </v-btn>
        </v-app-bar>


        <v-main>
            <router-view></router-view>
        </v-main>


        <div class="logout-dialog">
            <v-row justify="center">
                <v-dialog v-model="logoutDialog" persistent max-width="320">
                    <v-card>
                        <v-card-title class="headline">
                            تسجيل الخروج ؟
                        </v-card-title>
                        <v-card-text>
                            هل انت متأكد من رغبتك فى تسجيل الخروج ؟
                        </v-card-text>
                        <v-card-actions>
                            <v-spacer></v-spacer>
                            <v-btn color="error darken-1" depressed @click="logoutDialog = false">الغاء</v-btn>
                            <v-btn color="success darken-1" depressed @click="doLogout()">تأكيد</v-btn>
                        </v-card-actions>
                    </v-card>
                </v-dialog>
            </v-row>
        </div>

    </v-app>
</template>

<script>
    import Request from 'axios';

    export default {
        props: {
            source: String,
        },
        data: () => ({
            isSupervisor:false,
            super_id:false,
            logoutDialog: false,
            dialog: false,
            drawer: null,
            items: [
                { icon: 'mdi-plus', text: 'اضافة ناخب' , link: 'home'},
                { icon: 'mdi-cloud', text: 'الناخبين' , link: 'candidates' },
                { icon: 'mdi-clipboard-list', text: 'المسئولين' , link: 'supervisors' },
                { icon: 'mdi-plus', text: 'اضافة مسئول' , link: 'supervisor-create' },
                { icon: 'mdi-account-convert', text: 'تسجيل الخروج' , link : 'logout' },
            ],
            isLoading:false,
            CandidateSearch : null,
            candidatesResult : [],
            searchTerm : null,
        }),
        watch: {
            CandidateSearch(val){
                // orders have already been loaded
                // if (this.orderResults.length > 0) return

                // return;
                if (val == null) return;

                this.isLoading = true;

                Request.defaults.headers.common['Authorization'] = 'Bearer ' + localStorage.getItem('token');
                Request.get('/api/candidates/search' , {
                    params: {
                        term: this.CandidateSearch,
                    }
                }).then(response => {
                    this.candidatesResult = response.data;
                    this.isLoading = false;
                });

                // Lazily load input items
                // fetch('https://api.coingecko.com/api/v3/coins/list')
                //     .then(res => res.clone().json())
                //     .then(res => {
                //         this.orderResults = res
                //     })
                //     .catch(err => {
                //         console.log(err)
                //     })
                //     .finally(() => (this.isLoading = false));
            }
        },
        mounted(){
            this.isSupervisor = (localStorage.getItem('isSupervisor') == 'false') ? false : true;
            this.super_id = localStorage.getItem('super_id');

            if ( this.isSupervisor ) {
                this.items = [
                    { icon: 'mdi-plus', text: 'اضافة ناخب' , link: 'home'},
                    { icon: 'mdi-cloud', text: 'الناخبين' , link: 'candidates' },
                    // { icon: 'mdi-clipboard-list', text: 'المسئولين' , link: 'supervisors' },
                    // { icon: 'mdi-plus', text: 'اضافة مسئول' , link: 'supervisor-create' },
                    { icon: 'mdi-account-convert', text: 'تسجيل الخروج' , link : 'logout' },
                ];
            }
            // this.getAppData();
        },
        methods: {
            goTo(link){
                if ( link == 'logout' ) {
                    return this.openLogoutDialog();
                }
                this.$router.push({ name: link});
            },
            candidateLink(id){
                this.$router.push({ name: 'candidate-details', params: { id: id } })
            },
            openLogoutDialog(){
                this.logoutDialog = true;
                return true;
            },
            doLogout(){
                localStorage.removeItem('token');
                localStorage.removeItem('isSupervisor');
                localStorage.removeItem('super_id');
                return window.location.replace('/auth/');
            }
        }
    }
</script>