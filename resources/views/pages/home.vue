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

                <v-form
                    ref="form"
                    v-model="valid"
                    :lazy-validation="false"
                >

                    <v-text-field
                        v-model="uuid"
                        label="الرقم القومى"
                        autofocus
                    ></v-text-field>


                    <template v-if="errors">
                        <template v-for="(k , v) in errors">
                            <p>
                                <span class="red--text">{{ k[0] }}</span>
                            </p>
                        </template>
                    </template>
                    
                </v-form>

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
                step: 0,
                // uuid: "28609010106931",
                uuid: "",
                errors:{},
                valid: true,
                candidate:{
                    name: '',
                },
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
        },
        computed: {
        },
        watch: {
            $route(to, from) {
            }
        },
        methods: {
            displayInf(){

                if ( ! this.uuid ) {
                    this.notify.display = true;
                    this.notify.text = 'يجب ادخال الرقم القومى';
                    this.notify.color = 'red';
                    return false;
                }


                this.isLoading = true;
                Request.get('https://proxy.elections.eg/election' , {
                    params:{
                        nid: this.uuid,
                        location: 1,
                        cons: 1,
                        external_referrer: 'https://www.elections.eg/',
                        _: '1603207905253',
                    }
                }).then(payload => {
                    var response = payload.data;
                    console.log(this.uuid);
                    console.log(response);
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
                    uid: this.candidate.uid,
                    name: this.candidate.name,
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
