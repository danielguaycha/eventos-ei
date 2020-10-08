<template>
    <div class="card">
        <div class="card-header">
            <div >
                <b><i class="fa fa-user"></i>Listado de participantes ({{ laravelData.data ?  laravelData.data.length : 0}})</b>
            </div>
            <div class="d-flex">
                <DlgSendEmails v-model="dialogEmail"
                               ref="sendEmail"
                               v-if="event"
                               :event="event.id" @onSend="onSend"
                               :participantes="laravelData.data" />

                <DlgSearchStudent v-if="add" v-model="dialog"  @onSelect="addParticipant" ></DlgSearchStudent>
            </div>
        </div>
        <div class="card-body p-0">
            <Loader :loading="loader"></Loader>
            <div class="table-responsive table-bordered m-0">
                <table class="table table-bordered table-hover m-0 table-sm " v-if="!loader && laravelData">
                    <thead>
                    <tr class="align-middle">
                        <th>Estudiante</th>
                        <th>Cedula</th>
                        <th>Correo</th>
                        <th class="text-left" v-if="event.type !== 'asistencia'">
                            <span >Calif.</span></th>
                        <th>Certificado</th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="(p, index) in laravelData.data" :key="p.id">
                        <td data-name="Estudiante">{{ p.surname }} {{ p.name }}</td>
                        <td data-name="Cedula">{{ p.dni }}</td>
                        <td data-name="Correo">{{ p.email }}</td>
                        <td data-name="Calif." class="text-left" v-if="event.type !== 'asistencia'">
                            <b v-if="(p.nota_3 + p.nota_7) < 7 " class="text-danger">{{ p.nota_3 + p.nota_7 }}</b>
                            <b v-else class="text-success">{{ p.nota_3 + p.nota_7 }}</b>
                        </td>
                        <td data-name="Certificado">
                            <span v-if="p.status === 3">Enviado</span>
                            <span v-else>No enviado</span>
                        </td>
                        <td class="text-right">
                            <button type="button" v-if="viewBtnSend(p)"  :disabled="loaders[index].loader"
                                    @click="sendEmailCert(p, index)"
                                    title="Enviar certificado"
                                    class="btn btn-sm btn-outline-primary">
                                <i class="fa fa-envelope" v-if="!loaders[index].loader"></i>
                                <span class="spinner-border spinner-border-sm text-primary" role="status" v-else></span>
                            </button>
                            <button type="button" v-if="canDelete"
                                    @click="deleteParticipante(p)"
                                    class="btn btn-sm btn-outline-danger" >
                                <i class="fa fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="6" class="text-center text-muted py-2" v-if="laravelData.data && laravelData.data.length <=0">No hay participantes para este evento</td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-body py-0 text-center">
            <pagination :data="laravelData" @pagination-change-page="getParticipants"/>
        </div>
    </div>
</template>

<script>
import Loader from "./_partials/Loader";
import DlgSearchStudent from "./_dialog/DlgSearchStudent";
import DlgSendEmails from "./_dialog/DlgSendEmails";
export default {
    name: "ParticipantComponent",
    components: {DlgSendEmails, DlgSearchStudent, Loader},
    props: {
        event: {
            type: Number|String,
            default: null
        },
        add: {
            type: Boolean,
            default: false
        },
        canDelete: {
            type: Boolean,
            default: false
        },
        mail: {
            type: Boolean,
            default: false
        }
    },
    data() {
        return {
            loader: false,
            loaders: [],
            laravelData: {},
            dialog: false,
            dialogEmail: false
        }
    },
    mounted() {
      this.getParticipants();
    },
    methods: {
        getParticipants(page = 1) {
            if (!this.event) return;
            this.loader = true;
            axios.get(`/participantes/listar/${this.event.id}?page=${page}`).then(res => {
                if (res.data && res.data.ok) {
                    this.laravelData = res.data.body;
                    this.laravelData.data.forEach(e => {
                        this.loaders.push({id: e.id, loader: false})
                    });
                    if(this.mail) {
                        this.dialogEmail = true;
                        this.$refs.sendEmail.getParticipantes();
                    }
                }
            }).finally(() => this.loader = false);
        },
        deleteParticipante(p){
            let self = this;
            this.$dialog
                .confirm({title: 'Confirmar eliminación', body: `¿Esta seguro que desea eliminar a ${p.surname} de la lista de participantes?`}, {loader: true})
                .then(function(dialog) {
                    axios.delete(`/participantes/${p.id}`).then(res =>{
                        if (res.data.ok) {
                            const index = self.laravelData.data.indexOf(p);
                            if (index>= 0) {
                                self.laravelData.data.splice(index, 1);
                            }
                            self.$alert.ok(res.data.message);
                        }
                    }).finally(() => dialog.close());
                })
        },
        addParticipant(p) {
            if (this.laravelData.data.find(e => e.user_id === p.id)) {
                this.$alert.err("Este estudiante ya esta en la lista");
                return;
            }
            this.dialog = false;
            let self = this;
            this.$dialog
                .confirm({title: 'Agregar nuevo estudiante', body: `¿Esta seguro que desea agregar a ${p.surname} ${p.name} de la lista de participantes?`}, {loader: true})
                .then(function(dialog) {
                    axios.post(`/participantes/add`, {event_id: self.event.id, user_id: p.id}).then(res =>{
                        if (res.data.ok) {
                            self.laravelData.data.push({
                                name: p.name,
                                surname: p.surname,
                                dni: p.dni,
                                user_id: p.id,
                                email: p.email,
                                nota_3: 0,
                                nota_7: 0
                            });
                            self.loaders.push({id: p.id, loader: false})
                            self.$alert.ok(res.data.message);
                        }
                    }).finally(() => dialog.close());
                })
        },
        // enviar certificado
        sendEmailCert(p, index) {
            if (!p) return;
            this.loaders[index].loader = true;
            axios.get(`/events/sendmail/${p.id}`).then(res => {
                if (res.data.ok) {
                    this.$alert.ok(res.data.message);
                }
            }).catch(err => {
                this.$alert.err(err)
            }).finally(() =>  this.loaders[index].loader = false);
        },
        onSend(p) {
            this.laravelData.data.forEach(e => {
                if (e.id === p.id) {
                    e.status = p.status;
                    return;
                }
            })
        },
        viewBtnSend(p) {
            if (this.event.type === 'asistencia') {
                return true;
            }
            if (this.event.status >= 1 && (p.nota_3 + p.nota_7) >= 7) {
                return true;
            }
            return false;
        }
    },
}
</script>

<style scoped>

</style>
