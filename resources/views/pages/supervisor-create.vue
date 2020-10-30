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


            <v-card-text>
                <table cellspacing="0" class="table table-bordered custom">
                   <tbody>
                      <tr>
                         <th>الاسم : </th>
                         <td>
                            <v-text-field
                                v-model="supervisor.name"
                                label="الاسم بالكامل"
                                autofocus
                                required
                            ></v-text-field>
                         </td>
                      </tr>
                      <tr>
                         <th>الموبايل : </th>
                         <td>
                            <v-text-field
                                v-model="supervisor.mobile"
                                label="رقم الموبايل"
                                required
                            ></v-text-field>
                         </td>
                      </tr>
                      <tr>
                         <th>الموبايل 2 : </th>
                         <td>
                            <v-text-field
                                v-model="supervisor.mobile_2"
                                label="رقم الموبايل"
                                required
                            ></v-text-field>
                         </td>
                      </tr>
                      <tr>
                         <th> - </th>
                         <td>
                            <v-btn color="success darken-1" depressed @click="addNewSuper()">اضافة</v-btn>
                         </td>
                      </tr>
                   </tbody>
                </table>
            </v-card-text>

        </v-card>

    </div>
</template>

<script>
    import Request from 'axios';

    export default {
        name: 'supervisor-create',
        data () {
            return {
                breadcrumbsItems: [
                    {
                        text: 'كل المسئولين',
                        disabled: false,
                        href: '#/supervisors/',
                    },
                    {
                        text: 'اضافة المسئول',
                        disabled: true,
                        href: '',
                    },
                ],
                isLoading:false,
                supervisor:{
                    name: '',
                    mobile: '',
                    mobile_2: '',
                    username: '',
                    password: '',
                },
                notify:{
                    display: false,
                    text: '',
                    timeout: 3000,
                    color:'blue'
                }
            }
        },
        created() {
        },
        watch: {
            $route(to, from) {
            }
        },
        methods: {
            addNewSuper(){
                if ( ! this.supervisor.mobile || ! this.supervisor.name ) {
                    this.notify.display = true;
                    this.notify.text = 'كل الحقول ضروريه';
                    this.notify.color = 'error';
                    return false;
                }
                this.isLoading = true;
                Request.defaults.headers.common['Authorization'] = 'Bearer ' + localStorage.getItem('token');
                Request.post('/api/supervisors/', {
                        name: this.supervisor.name,
                        mobile: this.supervisor.mobile,
                        mobile_2: this.supervisor.mobile_2,
                        username: this.supervisor.username,
                        password: this.supervisor.password,
                }).then(response => {
                    // console.log(response);
                    this.notify.display = true;
                    this.notify.text = response.data.message;
                    this.notify.color = 'green';
                    this.isLoading = false;
                    this.supervisor = {};
                });
            },
        }
    }
</script>
