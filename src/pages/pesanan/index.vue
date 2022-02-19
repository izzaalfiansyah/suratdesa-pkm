<template>
    <v-card>
        <v-card-title>
            <v-row>
                <v-col cols="6"> Data Pesanan </v-col>
                <v-col cols="6" align="right">
                    <v-btn color="primary" @click="$router.push('/pesanan/create')">
                        <v-icon>mdi-plus</v-icon>
                        Tambah
                    </v-btn>
                </v-col>
            </v-row>
        </v-card-title>

        <v-card-text>
            <v-row justify="end">
                <v-col cols="12" lg="6">
                    <v-text-field placeholder="Cari Pesanan" prepend-icon="mdi-magnify" v-model="search"></v-text-field>
                </v-col>
            </v-row>
            <v-data-table loading-text="Memuat data..." :loading="is.loading" :headers="data.header" :items="datas" :search="search">
                <template slot="item.status" slot-scope="{ item }">
                    <v-chip small dark color="blue" v-if="item.status == '1'">Disetujui</v-chip>
                    <v-chip small dark color="red" v-else>Tidak Disetujui</v-chip>
                </template>

                <template slot="item.pratinjau">
                    <v-btn text color="red" small>
                        PDF
                    </v-btn>
                </template>

                <template slot="item.opsi" slot-scope="{ item }">
                    <v-btn icon color="blue" @click="$router.push('/pesanan/' + item.id)">
                        <v-icon>mdi-pen</v-icon>
                    </v-btn>
                    <v-btn icon color="red" @click="
                        data.delete = true;
                        req = item;
                    ">
                        <v-icon>mdi-delete</v-icon>
                    </v-btn>
                </template>
            </v-data-table>
        </v-card-text>

        <v-dialog v-model="data.delete">
            <v-form @submit.prevent="destroy">
                <v-card width="500">
                    <v-card-title>Hapus Pesanan</v-card-title>

                    <v-card-text>
                        Anda yakin menghapus data terpilih?
                    </v-card-text>

                    <v-card-actions>
                        <v-btn color="red" text type="submit">Hapus</v-btn>
                        <v-btn text type="button" @click="data.delete = false">Batal</v-btn>
                    </v-card-actions>
                </v-card>
            </v-form>
        </v-dialog>
    </v-card>
</template>

<script>
    export default {
        data() {
            return {
                datas: [],
                is: {
                    loading: true,
                },
                data: {
                    header: [
                        {
                            text: "Jenis Surat",
                            value: "surat.nama",
                        },
                        {
                            text: "Penanggung Jawab",
                            value: "penanggung_jawab",
                        },
                        {
                            text: "Status",
                            value: "status",
                        },
                        {
                            text: 'Pratinjau',
                            value: 'pratinjau'
                        },
                        {
                            text: "Opsi",
                            value: "opsi",
                            sortable: false,
                        },
                    ],
                    delete: false,
                },
                req: {},
                search: ''
            };
        },
        mounted() {
            this.get();
        },
        methods: {
            async get() {
                this.req = {};
                this.is.loading = true;
                await this.$root.http.get("/surat/pesanan").then((res) => {
                    this.datas = res.data;
                    this.is.loading = false;
                });
            },
            destroy() {
                this.$root.http.del("/surat/pesanan/" + this.req.id).then((res) => {
                    this.$root.notif(res.message, "green");
                    this.data.delete = false;
                    this.get();
                });
            },
        },
    };
</script>
