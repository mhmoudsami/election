<template>
    <div id="login-form">
        <v-overlay :value="isLoading">
            <v-progress-circular indeterminate size="64"></v-progress-circular>
        </v-overlay>

        <v-card class="elevation-12">
            <v-toolbar
                color="purple"
                dark
                flat
            >
                <v-toolbar-title>
                    تسجيل الدخول
                </v-toolbar-title>
            </v-toolbar>

            <v-card-text>
                <v-form
                    ref="form"
                    v-model="valid"
                    :lazy-validation="false"
                >
                    <v-text-field
                        v-model="login"
                        label="اسم المستخدم او البريد الالكترونى"
                        name="login"
                        :rules="loginRules"
                        prepend-icon="mdi-account"
                        type="text"
                        required
                        autofocus
                    />

                    <v-text-field
                        v-model="password"
                        id="password"
                        label="كلمة المرور"
                        name="password"
                        :rules="passwordRules"
                        prepend-icon="mdi-lock"
                        type="password"
                        required
                    />
                </v-form>
            </v-card-text>

            

            <v-card-actions>
                
                <v-spacer />
                <v-btn color="purple" @click="loginSubmit">
                    <span class="white--text">تسجيل الدخول</span>
                </v-btn>
            </v-card-actions>
        </v-card>

    </div>
</template>

<script>
    import authMixin from '../../js/mixins/authMixin';
    import commonMixin from '../../js/mixins/commonMixin';

    export default {
        props: {
            source: String,
        },
        data: () => ({
            valid: true,
            login: null,
            password: null,
            loginRules: [
                v => !!v || 'اسم المستخدم او البريد ضرورى',
            ],
            passwordRules: [
                v => !!v || 'كلمة المرور ضرورىه',
            ],
        }),
        mixins: [authMixin,commonMixin],
        methods: {
            loginSubmit(){
                this.$refs.form.validate();

                if ( ! this.valid ) {
                    return false;
                }
                return this.logAgentIn(this.login , this.password);
            }
        }
    }
</script>