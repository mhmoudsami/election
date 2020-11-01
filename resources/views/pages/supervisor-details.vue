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
                            @click="updateSupervisor()"
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
                <table cellspacing="0" class="table  custom">
                   <tbody>
                      <template v-if="editMode">
                          <tr>
                             <th>الاسم : </th>
                             <td>
                                <v-text-field
                                    v-model="supervisor.name"
                                    label="الاسم"
                                    autofocus
                                ></v-text-field>
                             </td>
                          </tr>
                          <tr>
                             <th>الموبايل : </th>
                             <td>
                                <v-text-field
                                    v-model="supervisor.mobile"
                                    label="الموبايل"
                                ></v-text-field>
                             </td>
                          </tr>
                          <tr>
                             <th>الموبايل 2: </th>
                             <td>
                                <v-text-field
                                    v-model="supervisor.mobile_2"
                                    label="الموبايل 2"
                                ></v-text-field>
                             </td>
                          </tr>

                          <tr>
                             <th>المدينه : </th>
                             <td>
                                <v-select
                                    v-model="supervisor.city_id"
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
                             </td>
                          </tr>
                          <tr>
                             <th>العنوان : </th>
                             <td>
                                <v-text-field
                                    v-model="supervisor.address"
                                    label="العنوان"
                                    required
                                ></v-text-field>
                             </td>
                          </tr>

                          <tr>
                             <th>اسم المستخدم: </th>
                             <td>
                                <v-text-field
                                    v-model="supervisor.username"
                                    label="اسم المستخدم"
                                ></v-text-field>
                             </td>
                          </tr>
                          <tr>
                             <th>كلمة المرور: </th>
                             <td>
                                <v-text-field
                                    v-model="supervisor.password"
                                    label="كلمة المرور"
                                ></v-text-field>
                             </td>
                          </tr>
                      </template>
                      <template v-else>
                          <tr>
                             <th>الاسم: </th>
                             <td>{{ supervisor.name }}</td>
                          </tr>
                          <tr>
                             <th>الموبايل: </th>
                             <td>{{ supervisor.mobile }}</td>
                          </tr>
                          <tr>
                             <th>الموبايل 2: </th>
                             <td>{{ supervisor.mobile_2 }}</td>
                          </tr>
                          <tr>
                             <th>المدينه: </th>
                             <td>{{ supervisor.city }}</td>
                          </tr>
                          <tr>
                             <th>العنوان: </th>
                             <td>{{ supervisor.address }}</td>
                          </tr>
                          <tr>
                             <th>اسم المستخدم: </th>
                             <td>{{ supervisor.username }}</td>
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
            updateSupervisor(){

                if ( ! this.supervisor.mobile || ! this.supervisor.name ) {
                    this.notify.display = true;
                    this.notify.text = 'ادخل الاسم ورقم الجوال';
                    this.notify.color = 'error';
                    return false;
                }

                this.isLoading = true;
                Request.defaults.headers.common['Authorization'] = 'Bearer ' + localStorage.getItem('token');
                Request.put('/api/supervisors/'+this.id , {
                        name : this.supervisor.name,
                        mobile: this.supervisor.mobile,
                        mobile_2: this.supervisor.mobile_2,

                        address: this.supervisor.address,
                        city_id: this.supervisor.city_id,

                        user_id: this.supervisor.user_id,
                        username: this.supervisor.username,
                        password: this.supervisor.password,
                }).then(response => {

                    if ( response.data.success ==  false ) {
                        this.notify.display = true;
                        this.notify.text = response.data.message;
                        this.notify.color = 'error';
                        this.isLoading = false;
                        return;
                    }

                    this.notify.display = true;
                    this.notify.text = response.data.message;
                    this.notify.color = 'green';
                    this.isLoading = false;
                    this.editMode = false;
                    location.reload();
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
