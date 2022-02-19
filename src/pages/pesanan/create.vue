<template>
    <v-form ref="create" @submit.prevent="store">
        <v-card>
            <v-card-title>Tambah Pesanan</v-card-title>

            <v-card-text>
                <v-autocomplete v-model="req.surat_id" @change="getSurat" :items="data.surat" label="Jenis Surat" :rules="[v => !!v || 'jenis surat harus dipilih']" placeholder="Pilih Jenis Surat" clearable></v-autocomplete>

                <v-text-field v-model="req.penanggung_jawab" label="Nama Penanggung Jawab" placeholder="Masukkan Nama Penanggung Jawab" :rules="[v => !!v || 'nama penanggung jawab harus diisi']"></v-text-field>

                <v-text-field v-model="req.nomor_telepon" label="Nomor Telepon" placeholder="Masukkan Nomor Telepon" :rules="[v => !!v || 'nomor telepon harus diisi']"></v-text-field>
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
            }
        },
        mounted() {
            this.getData();
        },
        methods: {
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
            store() {
                if (this.$refs.create.validate()) {
                    this.req['status'] = '0';
                    this.$root.http.post('/surat/pesanan', this.req).then(res => {
                        if (res.error) {
                            this.$root.notif(res.message[0]);
                        } else {
                            this.$root.notif(res.message, 'green');
                            this.$router.push('/pesanan');
                        }
                    })
                }
            }
        }
    };
</script>
