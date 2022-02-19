<template>
    <v-app>
        <dashboard></dashboard>

        <v-snackbar :color="notif.color" v-model="notif.open" timeout="3000">
            {{ notif.text }}
            <template slot="action">
                <v-btn icon color="white" @click="notif.open = false">X</v-btn>
            </template>
        </v-snackbar>
    </v-app>
</template>

<script>
    import Dashboard from "@/components/Dashboard.vue";

    export default {
        data() {
            return {
                notif: {
                    open: false,
                    text: '',
                    color: '',
                }
            }
        },
        components: {
            Dashboard,
        },
        created() {
            this.render();
        },
        methods: {
            render() {
                this.$root.app = "Surat Desa";
                this.$root.base_url = window.base_url ? window.base_url : "http://localhost:8000/api";
                this.$root.http = {
                    get: (path = "/", params = []) => {
                        const query = '?' + (new URLSearchParams(params)).toString();
                        return fetch(this.$root.base_url + path + query, {
                            method: 'GET',
                            headers: {
                                'Accept': 'application/json'
                            }
                        }).then(res => res.json());
                    },
                    post: (path = '/', params = [])  => {
                        return fetch(this.$root.base_url + path, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'Accept': 'application/json'
                            },
                            body: JSON.stringify(params)
                        }).then(res => res.json())
                    },
                    put: (path = '/', params = [])  => {
                        return fetch(this.$root.base_url + path, {
                            method: 'PUT',
                            headers: {
                                'Content-Type': 'application/json',
                                'Accept': 'application/json'
                            },
                            body: JSON.stringify(params)
                        }).then(res => res.json())
                    },
                    del: (path = '/') => {
                        return fetch(this.$root.base_url + path, {
                            method: 'DELETE'
                        }).then(res => res.json());
                    }
                };

                this.$root.notif = (message, color = '') => {
                    this.notif = {
                        open: true,
                        text: message,
                        color: color
                    }
                }
            },
        },
    };
</script>
