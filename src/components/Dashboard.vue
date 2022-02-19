<template>
    <div>
        <v-navigation-drawer app class="elevation-2" v-model="sidebar.open" width="300">
            <!-- <template v-slot:prepend>
                <v-list-item two-line class="py-1">
                    <v-list-item-avatar>
                        <img src="https://randomuser.me/api/portraits/women/81.jpg" />
                    </v-list-item-avatar>

                    <v-list-item-content>
                        <v-list-item-title>Jane Smith</v-list-item-title>
                        <v-list-item-subtitle class="text-sm">Logged In</v-list-item-subtitle>
                    </v-list-item-content>
                </v-list-item>
            </template>

            <v-divider></v-divider> -->

            <v-list>
                <v-subheader>MAIN MENU</v-subheader>
                <v-list-item-group color="primary">
                    <template v-for="(item, i) in sidebar.items">
                        <v-list-group v-if="item.children" :key="i" :prepend-icon="item.icon">
                            <template slot="activator">
                                <v-list-item-title>{{ item.title }}</v-list-item-title>
                            </template>
                            <v-list-item v-for="(child, j) in item.children" :key="j" :to="child.path" dense>
                                <v-list-item-icon>
                                    <v-icon>mdi-minus</v-icon>
                                </v-list-item-icon>
                                <v-list-item-content>
                                    <v-list-item-title>{{ child.title }}</v-list-item-title>
                                </v-list-item-content>
                            </v-list-item>
                        </v-list-group>
                        <v-list-item v-else :key="i" :to="item.path">
                            <v-list-item-icon>
                                <v-icon v-text="item.icon"></v-icon>
                            </v-list-item-icon>
                            <v-list-item-content>
                                <v-list-item-title>{{ item.title }}</v-list-item-title>
                            </v-list-item-content>
                        </v-list-item>
                    </template>
                    <v-list-item @click="modal.logout = true">
                        <v-list-item-icon>
                            <v-icon>mdi-logout</v-icon>
                        </v-list-item-icon>
                        <v-list-item-content>
                            <v-list-item-title>Logout</v-list-item-title>
                        </v-list-item-content>
                    </v-list-item>
                </v-list-item-group>
            </v-list>
        </v-navigation-drawer>

        <v-app-bar color="primary accent-4" dark app>
            <v-app-bar-nav-icon @click="toggleSidebar"></v-app-bar-nav-icon>

            <v-toolbar-title>{{ $root.app.toUpperCase() }}</v-toolbar-title>

            <v-spacer></v-spacer>

            <v-menu left bottom>
                <template v-slot:activator="{ on, attrs }">
                    <v-btn icon v-bind="attrs" v-on="on">
                        <v-icon>mdi-dots-vertical</v-icon>
                    </v-btn>
                </template>

                <v-list width="200">
                    <v-list-item to="/profil">
                        <v-list-item-title>Profil</v-list-item-title>
                    </v-list-item>
                    <v-list-item @click="modal.logout = true">
                        <v-list-item-title>Logout</v-list-item-title>
                    </v-list-item>
                </v-list>
            </v-menu>
        </v-app-bar>

        <v-main class="grey lighten-5">
            <v-container fluid>
                <router-view></router-view>
            </v-container>
        </v-main>

        <v-dialog v-model="modal.logout">
            <v-card width="500">
                <v-card-title>Logout</v-card-title>

                <v-card-text> Anda yakin akan logout? Sesi anda akan terhapus! </v-card-text>

                <v-card-actions>
                    <v-btn @click="logout" color="red" text>Keluar</v-btn>
                    <v-btn @click="modal.logout = false" text>Batal</v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                sidebar: {
                    open: true,
                    selectedItem: 0,
                    items: [
                        {
                            title: "Home",
                            icon: "mdi-home-city",
                            path: "/",
                        },
                        {
                            title: "Pengguna",
                            icon: "mdi-account",
                            path: "/user"
                        },
                        {
                            title: "Surat",
                            icon: "mdi-table",
                            path: "/surat",
                        },
                        {
                            title: "Pesanan",
                            icon: "mdi-calendar",
                            path: "/pesanan",
                        },
                        {
                            title: "Laporan",
                            icon: "mdi-book",
                            children: [
                                {
                                    title: "Pesanan Harian",
                                    path: "/laporan/pesanan/harian",
                                },
                                {
                                    title: "Pesanan Bulanan",
                                    path: "/laporan/pesanan/bulanan",
                                },
                            ],
                        },
                    ],
                },
                darkMode: false,
                modal: {
                    logout: false,
                },
            };
        },
        methods: {
            toggleSidebar() {
                this.sidebar.open = !this.sidebar.open;
            },
            logout() {
                this.modal.logout = false;
                this.$router.replace("/login");
            },
        },
    };
</script>
