<template>
    <table class="table table-bordered m-0">
        <thead>
        <tr>
            <th>Evento</th>
            <th>Fecha</th>
            <th>Tipo</th>
            <th class="text-center">Calificación</th>
            <th class="text-center">Certificado</th>
        </tr>
        </thead>
        <tbody>
        <tr v-for="(e, index) in events">
            <td data-name="Evento">
                <div>
                    {{ e.title }}
                </div>
            </td>
            <td data-name="Fecha">
                {{ e.f_fin }}
            </td>
            <td data-name="Tipo">
                <span v-if="e.type === 'asistencia'">Asistencia</span>
                <span v-if="e.type === 'aprobacion'">Aprobación</span>
                <span v-if="e.type === 'asistencia_aprobacion'">Asistencia y Aprobación</span>
            </td>
            <td class="text-center" data-name="Calificación">
                    <span v-if="e.type !== 'asistencia'">
                        <b>{{ e.nota_3 + e.nota_7 }}</b>
                    </span>
                <span v-else>-</span>
            </td>
            <td class="text-center" data-name="Certificado">
                <span class="mr-1" v-html="getStatus(e)"></span>
                <button v-if="viewBtnSend(e)" :disabled="loaders[index].loader"
                        class="btn btn-sm btn-outline-primary"
                        title="Enviar certificado"
                        type="button"
                        @click="sendEmailCert(e, index)">
                    <i v-if="!loaders[index].loader" class="fa fa-envelope"></i>
                    <span v-else class="spinner-border spinner-border-sm text-primary" role="status"></span>
                </button>

            </td>
        </tr>
        <tr>
            <td v-if="events && events.length <=0" class="no-data" colspan="5">
                El estudiante no registra ningún evento
            </td>
        </tr>
        </tbody>
    </table>
</template>

<script>
export default {
    name: "ShowStudent",
    props: {
        events: {
            type: Array | Object,
            required: true
        },
        canSend: {
            type: Boolean,
            default: false,
        },
    },
    data() {
        return {
            loaders: [],
        }
    },
    created() {
        this.loadLoaders();
    },
    methods: {
        loadLoaders() {
            this.events.forEach(e => {
                this.loaders.push({id: e.id, loader: false})
            });
        },

        sendEmailCert(p, index) {
            if (!p) return;
            this.loaders[index].loader = true;
            axios.get(`/events/sendmail/${p.id}`).then(res => {
                if (res.data.ok) {
                    this.$alert.ok(res.data.message);
                }
            }).catch(err => {
                this.$alert.err(err)
            }).finally(() => this.loaders[index].loader = false);
        },

        viewBtnSend(e) {
            if (!this.canSend) return false;
            if (e.type === 'asistencia') {
                return true;
            }
            if (e.status >= 1 && (e.nota_3 + e.nota_7) >= 7) {
                return true;
            }
            return false;
        },

        getStatus(e) {
            if (e.type !== 'asistencia') {
                if (e.nota_3 + e.nota_7 >= 7) { // mostrar estados
                    if (e.status === 3) return `<span class="text-success">Enviado</span>`;
                    else return `<span class="text-gray">Pendiente</span>`;
                } else {
                    return `<span class="text-danger">Reprobado</span>`;
                }
            } else {
                if (e.status === 3) return `<span class="text-success">Enviado</span>`;
                else return `<span class="text-gray">Pendiente</span>`;
            }
        }
    }
}
</script>

<style scoped>

</style>
