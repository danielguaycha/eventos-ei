<template>
    <div class="card">
        <div class="card-header">
            <div >
                <b><i class="fa fa-user"></i>Listado de participantes ({{ laravelData.data ?  laravelData.data.length : 0}})</b>
            </div>
            <div v-if="add">
                <DlgSearchStudent v-model="dialog"  @onSelect="addParticipant" ></DlgSearchStudent>
            </div>
        </div>
        <div class="card-body p-0">
            <Loader :loading="loader"></Loader>
            <div class="table-responsive table-bordered m-0">
                <table class="table table-hover m-0 table-sm " v-if="!loader && laravelData">
                    <thead>
                    <tr class="align-middle">
                        <th>Estudiante</th>
                        <th>Cedula</th>
                        <th>Correo</th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="p in laravelData.data" :key="p.id">
                        <td>{{ p.surname }} {{ p.name }}</td>
                        <td>{{ p.dni }}</td>
                        <td>{{ p.email }}</td>
                        <td class="text-right">
                            <button type="button" v-if="canDelete"
                                    @click="deleteParticipante(p)"
                                    class="btn btn-sm btn-outline-danger" >
                                <i class="fa fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="4" class="text-center text-muted py-2" v-if="laravelData.data && laravelData.data.length <=0">No hay participantes para este evento</td>
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
export default {
    name: "ParticipantComponent",
    components: {DlgSearchStudent, Loader},
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
        }
    },
    data() {
        return {
            loader: false,
            laravelData: {},
            dialog: false
        }
    },
    mounted() {
      this.getParticipants();
    },
    methods: {
        getParticipants(page = 1) {
            if (!this.event) return;
            this.loader = true;
            axios.get(`/participantes/listar/${this.event}?page=${page}`).then(res => {
                if (res.data && res.data.ok) {
                    this.laravelData = res.data.body;
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
                    axios.post(`/participantes/add`, {event_id: self.event, user_id: p.id}).then(res =>{
                        if (res.data.ok) {
                            self.laravelData.data.push({
                               name: p.name,
                               surname: p.surname,
                               dni: p.dni,
                               user_id: p.id,
                                email: p.email
                            });
                            self.$alert.ok(res.data.message);
                        }
                    }).finally(() => dialog.close());
                })
        }
    },
}
</script>

<style scoped>

</style>
