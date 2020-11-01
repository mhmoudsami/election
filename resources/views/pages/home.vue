<template>
    <div class="page-inner" id="scroll-target">

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


        <v-overlay :value="isLoading">
            <v-progress-circular indeterminate size="64"></v-progress-circular>
        </v-overlay>


        <v-subheader>
            البحث عن بيانات ناخب بالرقم القومى
        </v-subheader>

        <v-divider :class="'my-5'"></v-divider>


        <v-card
            class="mx-auto"
            tile
        >
            <div class="py-5 px-5">

                <v-text-field
                    v-model="uuid"
                    label="الرقم القومى"
                    autofocus
                    v-on:keydown.enter.prevent='displayInf()'
                ></v-text-field>


                <template v-if="errors">
                    <template v-for="(k , v) in errors">
                        <p>
                            <span class="red--text">{{ k[0] }}</span>
                        </p>
                    </template>
                </template>
                    

            </div>

            <v-footer
                padless
                class="font-weight-medium"
            >
                <v-col
                    class="text-left"
                    cols="12"
                >
                    <v-btn
                        color="primary"
                        class="mr-0"
                        @click="displayInf()"
                    >
                        عرض بيانات الناخب
                    </v-btn>
                </v-col>
            </v-footer>
            
        </v-card>

        <template v-if="exist">
            <v-card
                class="mx-auto mt-10"
                tile
            >
                <div class="clearfix"></div>
                <div class="danger-title">
                    بيانات الناخب المسجله
                </div>
                <div class="px-5 clearfix">
                    <table cellspacing="0" class="table table-bordered custom">
                       <tbody>
                          <tr>
                             <th>الاسم: </th>
                             <td>{{ candidate.name }}</td>
                          </tr>
                          <tr>
                             <th>المسئول: </th>
                             <td>{{ candidate.supervisor_name }}</td>
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
                       </tbody>
                    </table>
                </div>
            </v-card>
        </template>


        <template v-if="step==1">
            
            <v-form
                ref="form"
                v-model="valid"
                :lazy-validation="false"
            >

                <v-card
                    class="mx-auto mt-10"
                    tile
                >
                    <div class="title px-5 py-5">
                        بيانات اللجنة الانتخابية
                    </div>
                    <div class="px-5">

                        <v-text-field
                            v-model="candidate.name"
                            label="اكتب اسم الناخب بالكامل هنا"
                        ></v-text-field>

                        <template v-if="!isSupervisor">
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
                        </template>

                        <v-divider :class="'my-5'"></v-divider>

                        <table cellspacing="0" class="table table-bordered custom">
                           <tbody>
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
                           </tbody>
                        </table>

                    </div>

                    <v-footer
                        padless
                        class="font-weight-medium"
                    >
                        <v-col
                            class="text-left"
                            cols="12"
                        >
                            <v-btn
                                color="primary"
                                class="mr-0"
                                @click="savecandidateInfo()"
                            >
                                حفظ بيانات الناخب
                            </v-btn>
                        </v-col>
                    </v-footer>
                    
                </v-card>

            </v-form>
        </template>

        


    </div>
</template>

<script>
    import Request from 'axios';

    export default {
        name: 'Home',
        data () {
            return {
                exist: false,
                step: 0,
                // uuid: "28609010106931",
                uuid: "",
                errors:{},
                valid: true,
                candidate:{
                    name: '',
                },
                supervisor:null,
                supervisorSync:null,
                supervisors:[],
                isLoading: false,
                notify:{
                    display: false,
                    text: '',
                    timeout: 3000,
                    color:'blue'
                }
            }
        },
        created() {
            this.isSupervisor = (localStorage.getItem('isSupervisor') == 'false') ? false : true;
            this.super_id = localStorage.getItem('super_id');

            this.getAppData();
        },
        computed: {
        },
        watch: {
            $route(to, from) {
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
            displayInf(){

                this.exist = false;

                if ( ! this.uuid ) {
                    this.notify.display = true;
                    this.notify.text = 'يجب ادخال الرقم القومى';
                    this.notify.color = 'red';
                    return false;
                }


                this.isLoading = true;
                Request.get('/api/proxy/'+this.uuid , {
                    params:{
                    }
                }).then(payload => {
                    var response = payload.data;

                    if ( response.success == false ) {
                        this.notify.display = true;
                        this.notify.text = response.message;
                        this.notify.color = 'error';
                        this.isLoading = false;
                        return;
                    }

                    if ( response.exist ) {
                        this.exist = true;
                        this.candidate = response.payload;
                        this.isLoading = false;
                        return;
                    }

                    if ( response.status == 'INVALID' ) {
                        this.notify.display = true;
                        this.notify.text = response.rejection_reason.description;
                        this.isLoading = false;

                        return;
                    }

                    // console.log(this.uuid);
                    // console.log(response);
                    this.candidate = {
                        uid: this.uuid,
                        name: this.candidate.name,
                        location: response.voting_info.locations[0].name,
                        address: response.voting_info.locations[0].unparsed_address,
                        state: response.state_info.gov_name,
                        police: response.state_info.police_name,
                        boxnumber: response.voting_info.box_number,
                        citizen_number: response.voting_info.citizen_number,
                        stage: response.voting_info.stage,
                        date_round_1: response.voting_info.date_round_1,
                        date_round_2: response.voting_info.date_round_1,
                        indiv_const: response.constituencies.indiv_const.name,
                        list_const: response.constituencies.list_const.name,
                        all:response
                    };

                    this.step = 1;

                    this.isLoading = false;
                }).catch(error => {
                    console.log(error);
                    this.notify.display = true;
                    this.notify.text = 'حدث خطأ ما , رجاء اعادة المحاوله';
                    this.isLoading = false;
                });
            },
            savecandidateInfo(){


                if ( ! this.candidate.name ) {
                    this.notify.display = true;
                    this.notify.text = 'يجب ادخال اسم الناخب';
                    this.notify.color = 'red';
                    return false;
                }

                this.isLoading = true;
                Request.defaults.headers.common['Authorization'] = 'Bearer ' + localStorage.getItem('token');
                Request.post('/api/candidates' , {
                    super_id: this.super_id,
                    uid: this.candidate.uid,
                    name: this.candidate.name,
                    supervisor: this.supervisor,
                    location: this.candidate.location,
                    address: this.candidate.address,
                    state: this.candidate.state,
                    police: this.candidate.police,
                    boxnumber: this.candidate.boxnumber,
                    citizen_number: this.candidate.citizen_number,
                    stage: this.candidate.stage,
                    date_round_1: this.candidate.date_round_1,
                    date_round_2: this.candidate.date_round_2,
                    indiv_const: this.candidate.indiv_const,
                    list_const: this.candidate.list_const,
                    all:this.candidate.all,
                }).then(response => {
                    this.isLoading = false;

                    if (! response.data.success) {
                        this.notify.display = true;
                        this.notify.text = response.data.message;
                    }

                    if ( response.data.success ) {
                        this.notify.display = true;
                        this.notify.text = response.data.message;
                        this.notify.color = 'green';
                        this.afterSave();
                    }
                }).catch(error => {
                    this.notify.display = true;
                    this.notify.text = 'حدث خطأ ما , رجاء اعادة المحاوله';
                    this.isLoading = false;
                });
            },
            afterSave(){
                this.candidate = {};
                this.step = 0;
                this.uuid = '';
            },
        }
    }
</script>
