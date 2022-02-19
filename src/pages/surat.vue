<template>
    <v-card>
        <v-card-title>
            <v-row>
                <v-col cols="6"> Data Surat </v-col>
                <v-col cols="6" align="right">
                    <v-btn
                        color="primary"
                        @click="
                            req = {};
                            rules = [{ kolom: '', tipe: '' }];
                            $refs.save ? $refs.save.resetValidation() : null;
                            modal.save = true;
                        "
                    >
                        <v-icon>mdi-plus</v-icon>
                        Tambah
                    </v-btn>
                </v-col>
            </v-row>
        </v-card-title>
        <v-card-text>
            <v-row justify="end">
                <v-col cols="12" lg="6">
                    <v-text-field placeholder="Cari Nama Surat" prepend-icon="mdi-magnify" v-model="search"></v-text-field>
                </v-col>
            </v-row>
            <v-data-table :loading="is.loading" loading-text="Memuat data...." :headers="data.header" :items="datas" :search="search">
                <template slot="item.rules" slot-scope="{ item }">
                    <div style="max-width: 600px">
                        <span v-for="(rule, i) in item.rules" :key="i"> 
                            {{ rule.kolom + (i + 1 < item.rules.length ? "," : "") }} 
                        </span>
                    </div>
                </template>

                <template slot="item.opsi" slot-scope="{ item }">
                    <v-btn
                        @click="
                            req = item;
                            rules = [];
                            item.rules.forEach((i) => {
                                rules.push({
                                    kolom: i.kolom,
                                    tipe: typeof i.tipe == 'string' ? i.tipe : 'select',
                                    select: typeof i.tipe == 'string' ? '' : i.tipe.join('/'),
                                });
                            });
                            modal.save = true;
                        "
                        icon
                        color="blue"
                        small
                        ><v-icon>mdi-pen</v-icon></v-btn
                    >
                    <v-btn
                        @click="
                            req = item;
                            modal.delete = true;
                        "
                        icon
                        color="red"
                        small
                        ><v-icon>mdi-delete</v-icon></v-btn
                    >
                </template>
            </v-data-table>
        </v-card-text>
        <v-dialog v-model="modal.save" fullscreen hide-overlay class="white">
            <v-form @submit.prevent="save" ref="save" style="height: auto 100%">
                <v-card style="min-height: 100vh; height: 100%">
                    <v-card-title>{{ req.id ? "Edit" : "Tambah" }} Surat</v-card-title>

                    <v-card-text>
                        <v-text-field label="Nama Surat" v-model="req.nama" :rules="[(v) => !!v || 'nama surat harus diisi']" placeholder="Masukkan Nama Surat"></v-text-field>

                        <v-row v-for="(item, i) in rules" :key="i">
                            <v-col cols="6">
                                <v-text-field v-model="item.kolom" placeholder="Masukkan Nama Kolom" :label="'Kolom Ke-' + (i + 1)" :rules="[(v) => !!v || 'nama kolom harus diisi']"></v-text-field>
                            </v-col>
                            <v-col cols="6">
                                <v-select v-model="item.tipe" label="Tipe Kolom" :rules="[(v) => !!v || 'tipe kolom harus dipilih']" :items="form.tipe"></v-select>
                            </v-col>
                            <v-col v-if="item.tipe == 'select'" cols="12">
                                <v-text-field label="Isi Pilihan" placeholder="Pilihan 1/Pilihan 2/Pilihan 3" :rules="[(v) => !!v || 'pilihan harus diisi', (v) => !(v ? v.indexOf('/') < 0 : true) || 'pilihan harus dipisahkan dengan tanda (/)']" v-model="item.select"></v-text-field>
                            </v-col>
                        </v-row>

                        <v-btn block color="primary" @click="rules.push({ kolom: '', tipe: '' })">Tambah Kolom Isian</v-btn>
                    </v-card-text>

                    <v-card-actions style="position: sticky; bottom: 0; left: 0; right 0;" class="white">
                        <v-btn color="primary" type="submit" text>Simpan</v-btn>
                        <v-btn type="button" @click="modal.save = false" text>Batal</v-btn>
                    </v-card-actions>
                </v-card>
            </v-form>
        </v-dialog>
        <v-dialog v-model="modal.delete">
            <v-form @submit.prevent="destroy">
                <v-card width="500">
                    <v-card-title>Hapus Surat</v-card-title>

                    <v-card-text>
                        Anda yakin menghapus surat <strong>{{ req.nama }}?</strong>
                    </v-card-text>

                    <v-card-actions>
                        <v-btn color="red" type="submit" text>Hapus</v-btn>
                        <v-btn type="button" @click="modal.delete = false" text>Batal</v-btn>
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
                is: {
                    loading: true,
                },
                datas: [],
                search: "",
                modal: {
                    save: false,
                    delete: false,
                },
                req: {},
                rules: [],
                form: {
                    tipe: [
                        {
                            text: "Kalimat",
                            value: "text",
                        },
                        {
                            text: "Angka",
                            value: "number",
                        },
                        {
                            text: "Tanggal",
                            value: "date",
                        },
                        {
                            text: "Pilihan",
                            value: "select",
                        },
                    ],
                },
                data: {
                    header: [
                        {
                            text: "Nama Surat",
                            value: "nama",
                        },
                        {
                            text: "Atribut",
                            value: "rules",
                        },
                        {
                            text: "Opsi",
                            value: "opsi",
                            sortable: false,
                        },
                    ],
                },
            };
        },
        mounted() {
            this.get();
        },
        methods: {
            async get() {
                this.rules = [];
                this.rules.push({
                    kolom: "",
                    tipe: "",
                });
                this.req = {};
                this.is.loading = true;
                await this.$root.http.get("/surat").then((res) => {
                    this.datas = res.data;
                    this.is.loading = false;
                });
            },
            save() {
                this.req.rules = [];
                this.rules.forEach((item) => {
                    this.req.rules.push({
                        kolom: item.kolom,
                        tipe: item.tipe == "select" ? item.select.split("/") : item.tipe,
                    });
                });
                if (this.$refs.save.validate()) {
                    if (this.req.id) {
                        this.$root.http.put("/surat/" + this.req.id, this.req).then((res) => {
                            if (res.error) {
                                this.$root.notif(res.message[0]);
                            } else {
                                this.$root.notif(res.message, "green");
                                this.modal.save = false;
                                this.get();
                            }
                        });
                    } else {
                        this.$root.http.post("/surat", this.req).then((res) => {
                            if (res.error) {
                                this.$root.notif(res.message[0]);
                            } else {
                                this.$root.notif(res.message, "green");
                                this.modal.save = false;
                                this.get();
                            }
                        });
                    }
                }
            },
            destroy() {
                this.$root.http.del("/surat/" + this.req.id).then((res) => {
                    if (res.error) {
                        this.$root.notif(res.message[0]);
                    } else {
                        this.$root.notif(res.message, "green");
                        this.modal.delete = false;
                        this.get();
                    }
                });
            },
        },
    };
</script>
