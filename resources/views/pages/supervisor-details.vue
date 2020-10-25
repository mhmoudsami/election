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
                         <th>الاسم : </th>
                         <td>
                            <v-text-field
                                v-model="supervisor.name"
                                label=""
                                autofocus
                            ></v-text-field>
                         </td>
                      </tr>
                      <tr v-if="editMode">
                         <th>الموبايل : </th>
                         <td>
                            <v-text-field
                                v-model="supervisor.mobile"
                                label=""
                            ></v-text-field>
                         </td>
                      </tr>
                      <template v-if="!editMode">
                          <tr>
                             <th>الاسم: </th>
                             <td>{{ supervisor.name }}</td>
                          </tr>
                          <tr>
                             <th>الرقم الموبايل: </th>
                             <td>{{ supervisor.mobile }}</td>
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
                            هل انت متأكد من رغبتك فى حذف بيانات هذا المسئول ؟
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
        name: 'SupervisorDetails',
        data () {
            return {
                id: null,
                confirmDialog: false,
                editMode: false,
                isLoading:false,
                supervisor:{},
                breadcrumbsItems: [
                    {
                        text: 'كل المسئولين',
                        disabled: false,
                        href: '#/supervisors',
                    },
                    {
                        text: 'تفاصيل المسئول',
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
            this.getSupervisorDetails(this.id);
        },
        watch: {
            $route(to, from) {
                this.id = to.params.id;
                this.getSupervisorDetails(to.params.id);
            }
        },
        methods: {
            getSupervisorDetails(id){
                this.isLoading = true;
                Request.defaults.headers.common['Authorization'] = 'Bearer ' + localStorage.getItem('token');
                Request.get('/api/supervisors/'+id).then(response => {
                    
                    this.supervisor = response.data;
                    this.isLoading = false;
                });
            },
            enebleEditMode(){
                this.editMode = true;
            },
            updateCandidate(){
                this.isLoading = true;
                Request.defaults.headers.common['Authorization'] = 'Bearer ' + localStorage.getItem('token');
                Request.put('/api/supervisors/'+this.id , {
                        name : this.supervisor.name,
                        mobile: this.supervisor.mobile,
                }).then(response => {
                    this.notify.display = true;
                    this.notify.text = response.data.message;
                    this.notify.color = 'green';
                    this.isLoading = false;
                    this.editMode = false;
                });
            },
            deleteClicked(){
                this.confirmDialog = true;
            },
            doConfirmDelete(){
                this.confirmDialog = false;
                // delete
                this.isLoading = true;
                Request.defaults.headers.common['Authorization'] = 'Bearer ' + localStorage.getItem('token');
                Request.delete('/api/supervisors/'+this.id).then(response => {
                    
                    if (! response.data.success) {
                        this.notify.display = true;
                        this.notify.text = response.data.message;
                        this.notify.color = 'red';
                    }
                    if ( response.data.success) {
                        this.notify.display = true;
                        this.notify.text = response.data.message;
                        this.notify.color = 'green';
                        return window.location.replace('/#/supervisors');
                    }

                    this.isLoading = false;
                });
            },
        }
    }
</script>
