<template>
    <div>
        <button class="btn btn-sm btn-outline-primary mr-1"
                @click="getParticipantes()"><i class="fa fa-envelope mr-1"></i>Enviar certificados
        </button>
        <Dialog v-model="show" alert>
            <div class="dlg-header"><b>Enviar certificados</b></div>
            <div class="dlg-body d-flex align-items-center justify-content-center">
                <div v-if="loader" class="loading-email">
                    <span class="spinner-border text-primary py-2" role="status"></span>
                    <i class="fa fa-envelope"></i>
                    <small class="text-muted">Enviando correos...</small>
                    <h4 v-if="participantes" class="text-muted"><b>{{ process }}</b>/<b>{{ participantes.length }}</b>
                    </h4>
                </div>
            </div>
            <div class="dlg-footer text-right">
                <button class="btn btn-danger" @click="show = false; cancelRequest()">Cancelar</button>
            </div>
        </Dialog>
        <dlg-confirm ref="confirm"></dlg-confirm>
    </div>
</template>

<script>
import Dialog from "../_partials/Dialog";
import DlgConfirm from "./DlgConfirm";

export default {
    name: "DlgSendEmails",
    props: {
        value: Boolean,
        event: {
            type: Number,
            required: true
        },
    },
    components: {DlgConfirm, Dialog},
    data() {
        return {
            loader: true,
            process: 0,
            cancelSource: null,
            participantes: []
        }
    },
    created() {

    },
    methods: {
        async getParticipantes() {
            const confirm = await this.$refs.confirm.open(
                "Confirmar envío de certificados",
                `¿Esta seguro de que desea enviar todos los certificados pendientes a los estudiantes?`
            )
            if (!confirm) {
                return;
            }
            this.show = true;
            this.loader = true;
            axios.get(`/participantes/aprobados/${this.event}`).then(res => {
                if (res.data.ok) {
                    this.participantes = res.data.body;
                    if (this.participantes.length > 0) {

                        this.sendEmails();

                    } else {
                        this.$alert.err("No existen participantes aprobados!");
                        this.show = false;
                    }
                }
            })
                //.finally(() => this.loader = false);
        },
        async sendEmails() {
            this.process = 0;
            this.cancelSource = axios.CancelToken.source();
            this.loader = true;
            let sent = 0;
            for (const p of this.participantes) {
               if (p.status <= 2 ) {
                    try {
                        await axios.get(`/events/sendmail/${p.id}`, {cancelToken: this.cancelSource.token });
                        //await new Promise(resolve => setTimeout(resolve, 10000));
                        this.$emit('onSend', {id: p.id, status: 3});
                        sent++;
                    } catch (e) {
                        //this.$alert.err(e);
                        this.show = false;
                        return;
                    }
               }
               this.process++;
            }
            this.cancelSource = null;
            if (sent<=0) {
                this.$alert.ok("Todos los certificados ya fueron entregados!");
            } else {
                this.$alert.ok(`Se emitieron ${sent} certificados`);
            }
            this.show = false;
        },
        cancelRequest () {
            if (this.cancelSource) {
                this.cancelSource.cancel('Start new search, stop active search');
            }
        }
    },
    computed: {
        show: {
            get () {
                return this.value
            },
            set (value) {
                this.$emit('input', value)
            }
        },
    },
}
</script>

<style scoped>

</style>
