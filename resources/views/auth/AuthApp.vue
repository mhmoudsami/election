<template>
    <v-app id="go-auth-main-app">

        <v-overlay :value="overlay">
            <v-progress-circular indeterminate size="64"></v-progress-circular>
        </v-overlay>

        <v-app-bar
            :clipped-right="$vuetify.breakpoint.lgAndUp"
            app
            color="purple"
            dark
        >

            <v-toolbar-title
                style="width: 300px"
                class="ml-0 pl-4"
            >
                <span class="hidden-sm-and-down">
                    Election
                </span>
            </v-toolbar-title>

            <v-spacer />

            <v-btn icon @click="goTo('login')">
                <v-icon>mdi-home</v-icon>
            </v-btn>

        </v-app-bar>

        <v-container
            class="fill-height"
            fluid
        >
            <v-row
                align="center"
                justify="center"
            >
                <v-col
                cols="12"
                sm="8"
                md="4"
                >
                    <v-content>
                        <router-view></router-view>
                    </v-content>
                </v-col>
                
            </v-row>
        </v-container>

    </v-app>
</template>

<script>
    import commonMixin from '../../js/mixins/commonMixin';

    export default {
        props: {
            source: String,
        },
        data: () => ({
            test: false,
            overlay: false,
        }),
        mixins: [commonMixin],
        methods: {
        }
    }
</script>