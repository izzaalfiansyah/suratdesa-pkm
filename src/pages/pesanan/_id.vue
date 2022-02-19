<template>
    <v-form ref="edit" @submit.prevent="update">
        <v-card>
            <v-card-title>Edit Pesanan</v-card-title>

            <v-card-text>
                <v-autocomplete :loading="is.loading" v-model="req.surat_id" @change="getSurat" :items="data.surat" label="Jenis Surat" :rules="[v => !!v || 'jenis surat harus dipilih']" placeholder="Pilih Jenis Surat" clearable></v-autocomplete>

                <v-text-field :loading="is.loading" v-model="req.penanggung_jawab" label="Nama Penanggung Jawab" placeholder="Masukkan Nama Penanggung Jawab" :rules="[v => !!v || 'nama penanggung jawab harus diisi']"></v-text-field>

                <v-text-field :loading="is.loading" v-model="req.nomor_telepon" label="Nomor Telepon" placeholder="Masukkan Nomor Telepon" :rules="[v => !!v || 'nomor telepon harus diisi']"></v-text-field>

                <v-select :loading="is.loading" v-model="req.status" label="Status" :rules="[v => !!v || 'status harus dipilih']" :items="[{ value: '1', text: 'Disetujui' }, { value: '0', text: 'Tidak Disetujui' }]"></v-select>
                <v-divider></v-divider>
            </v-card-text>

            <div v-if="data.surat_pilihan.id">
                <v-card-title>Surat {{ data.surat_pilihan.nama }}</v-card-title>
                
                <v-card-text>
                    <template v-for="(item, i) in data.surat_pilihan.rules">
                        <v-select v-model="req.values[item.kolom]" :key="i" v-if="(typeof item.tipe) !== 'string'" :label="item.kolom" :items="item.tipe" :rules="[v => !!v || item.kolom.toLowerCase() + ' harus dipilih']"></v-select>
                        <v-text-field v-model="req.values[item.kolom]" :key="i" v-else :type="item.tipe" :label="item.kolom" :placeholder="'Masukkan ' + item.kolom" :rules="[v => !!v || item.kolom.toLowerCase() + ' harus diisi']"></v-text-field>
                    </template>
                </v-card-text>
            </div>

            <v-card-actions>
                <v-btn type="submit" color="primary" text>Simpan</v-btn>
                <v-btn type="button" text @click="$router.push('/pesanan')">Kembali</v-btn>
            </v-card-actions>
        </v-card>
    </v-form>
</template>

<script>
    export default {
        data() {
            return {
                data: {
                    surat: [],
                    surat_pilihan: {},
                },
                req: {
                    values: {}
                },
                is: {
                    loading: true,
                }
            }
        },
        mounted() {
            this.getData();
            this.get();
        },
        methods: {
            async get() {
                await this.$root.http.get('/surat/pesanan/' + this.$route.params.id).then(res => {
                    this.req = res.data;
                    this.getSurat(res.data.surat_id);
                    this.is.loading = false;
                });
            },
            async getData() {
                await this.$root.http.get('/surat').then(res => {
                    this.data.surat = [];
                    res.data.forEach(item => {
                        this.data.surat.push({
                            text: item.nama,
                            value: item.id
                        });
                    });
                });
            },
            async getSurat(val) {
                await this.$root.http.get('/surat/' + val).then(res => {
                    this.data.surat_pilihan = res.data;
                })
            },
            update() {
                if (this.$refs.edit.validate()) {
                    this.is.loading = true;
                    this.$root.http.put('/surat/pesanan/' + this.$route.params.id, this.req).then(res => {
                        if (res.error) {
                            this.$root.notif(res.message[0]);
                        } else {
                            this.$root.notif(res.message, 'green');
                        }
                    })
                }
            }
        }
    };
</script>
