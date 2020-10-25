<template>
    <div class="page-inner" v-cloak>
        <v-overlay :value="isLoading">
            <v-progress-circular indeterminate size="64"></v-progress-circular>
        </v-overlay>

        <v-breadcrumbs :items="breadcrumbsItems"></v-breadcrumbs>

        <v-snackbar
        v-model="notify.display"
        :timeout="notify.timeout"
        :color='notify.color'
        top
        center
        >
            {{ notify.text }}

            <template v-slot:action="{ attrs }">
                <v-btn
                    color="white"
                    text
                    v-bind="attrs"
                    @click="notify.display = false"
                >
                    اغلاق
                </v-btn>
            </template>
        </v-snackbar>


        <v-divider :class="'my-5'"></v-divider>

        <v-card
            class="mx-auto"
            tile
        >

            <div class="pr-2">
                <v-card-actions>
                    <template v-if="editMode">
                        <v-btn
                            depressed
                            color="success"
                            @click="updateCandidate()"
                        >
                            حفظ
                        </v-btn>
                        <v-btn
                            depressed
                            color="info"
                            @click="editMode=false"
                        >
                            الغاء
                        </v-btn>
                    </template>
                    <template v-else>
                        <v-btn
                            depressed
                            color="primary"
                            @click="enebleEditMode()"
                        >
                            تعديل
                        </v-btn>
                        <v-btn
                            depressed
                            color="error"
                            @click="deleteClicked()"
                        >
                            حذف
                        </v-btn>
                    </template>
                </v-card-actions>
            </div>

            <v-card-text>
                <table cellspacing="0" class="table table-bordered custom">
                   <tbody>
                      <tr v-if="editMode">
                         <th>الاسم بالكامل: </th>
                         <td>
                            <v-text-field
                                v-model="candidate.name"
                                label=""
                                autofocus
                            ></v-text-field>
                         </td>
                      </tr>
                      <tr v-if="editMode">
                         <th>المسئول: </th>
                         <td>
                            <v-select
                                v-model="supervisor"
                                :items="supervisors"
                                label="المسئول"
                                item-text="name"
                                item-value="id"
                                required
                            ></v-select>
                         </td>
                      </tr>
                      <template v-if="!editMode">
                          <tr>
                             <th>الاسم: </th>
                             <td>{{ candidate.name }}</td>
                          </tr>
                          <tr>
                             <th>الرقم القومى: </th>
                             <td>{{ candidate.uid }}</td>
                          </tr>
                          <tr>
                             <th>مركزك الإنتخابي: </th>
                             <td>{{ candidate.location }}</td>
                          </tr>
                          <tr>
                             <th>محافظة: </th>
                             <td>{{ candidate.state }}</td>
                          </tr>
                          <tr>
                             <th>قسم: </th>
                             <td>{{ candidate.police }}</td>
                          </tr>
                          <tr>
                             <th>العنوان : </th>
                             <td>{{ candidate.address }}</td>
                          </tr>
                          <tr>
                             <th>رقم اللجنة الفرعية: </th>
                             <td>{{ candidate.boxnumber }}</td>
                          </tr>
                          <tr>
                             <th>رقمك في الكشوف الانتخابية: </th>
                             <td>{{ candidate.citizen_number }}</td>
                          </tr>
                          <tr>
                             <th>تاريخ التصويت: </th>
                             <td>{{ candidate.date_round_1 }}</td>
                          </tr>
                          <tr>
                             <th>دائرة الفردي: </th>
                             <td>
                                {{ candidate.indiv_const }}
                             </td>
                          </tr>
                          <tr>
                             <th>دائرة القائمة: </th>
                             <td>
                                {{ candidate.list_const }}
                             </td>
                          </tr>
                      </template>
                   </tbody>
                </table>
            </v-card-text>

        </v-card>



        <div class="confirm-dialog">
            <v-row justify="center">
                <v-dialog v-model="confirmDialog" persistent max-width="320">
                    <v-card>
                        <v-card-title class="headline">
                            تأكيد
                        </v-card-title>
                        <v-card-text>
                            هل انت متأكد من رغبتك فى حذف بيانات هذا الناخب ؟
                        </v-card-text>
                        <v-card-actions>
                            <v-spacer></v-spacer>
                            <v-btn color="error darken-1" depressed @click="confirmDialog = false">الغاء</v-btn>
                            <v-btn color="success darken-1" depressed @click="doConfirmDelete()">تأكيد</v-btn>
                        </v-card-actions>
                    </v-card>
                </v-dialog>
            </v-row>
        </div>

    </div>
</template>

<script>
    import Request from 'axios';

    export default {
        name: 'CandidateDetails',
        data () {
            return {
                id: null,
                candidate:{},
                confirmDialog: false,
                editMode: false,
                isLoading:false,
                supervisors:[],
                supervisor:null,
                breadcrumbsItems: [
                    {
                        text: 'كل الناخبين',
                        disabled: false,
                        href: '#/candidates',
                    },
                    {
                        text: 'تفاصيل الطلب',
                        disabled: true,
                        href: '',
                    },
                ],
                notify:{
                    display: false,
                    text: '',
                    timeout: 3000,
                    color:'blue'
                }
            }
        },
        created() {
            this.id = this.$route.params.id;
            this.getCandidateDetails(this.id);
            this.getAppData();
        },
        watch: {
            $route(to, from) {
                this.id = to.params.id;
                this.getCandidateDetails(to.params.id);
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
            getCandidateDetails(id){
                this.isLoading = true;
                Request.defaults.headers.common['Authorization'] = 'Bearer ' + localStorage.getItem('token');
                Request.get('/api/candidates/'+id , {
                    params: {
                        id : id,
                    }
                }).then(response => {
                    console.log(response.data);
                    this.candidate = response.data;
                    this.isLoading = false;
                });
            },
            enebleEditMode(){
                this.editMode = true;
            },
            doConfirmDelete(){
                this.confirmDialog = false;
                // delete
                this.isLoading = true;
                Request.defaults.headers.common['Authorization'] = 'Bearer ' + localStorage.getItem('token');
                Request.delete('/api/candidates/'+this.id).then(response => {
                    
                    if (! response.data.success) {
                        this.notify.display = true;
                        this.notify.text = response.data.message;
                        this.notify.color = 'red';
                    }
                    if ( response.data.success) {
                        this.notify.display = true;
                        this.notify.text = response.data.message;
                        this.notify.color = 'green';
                        return window.location.replace('/#/candidates');
                    }

                    this.isLoading = false;
                });
            },
            deleteClicked(){
                this.confirmDialog = true;
            },
            updateCandidate(){
                this.isLoading = true;
                Request.defaults.headers.common['Authorization'] = 'Bearer ' + localStorage.getItem('token');
                Request.put('/api/candidates/'+this.id , {
                        id : this.id,
                        name: this.candidate.name,
                        supervisor_id: this.supervisor
                }).then(response => {
                    this.notify.display = true;
                    this.notify.text = response.data.message;
                    this.notify.color = 'green';
                    this.isLoading = false;
                    this.editMode = false;
                });
            },
        }
    }
</script>
