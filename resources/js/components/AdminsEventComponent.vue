<template>
    <div class="card">
        <div class="card-header">
            <div >
                <b><i class="fa fa-user"></i>Listado de administradores ({{ laravelData.data ?  laravelData.data.length : 0}})</b>
            </div>
            <div v-if="canAdd">
                <DlgSearchStudent v-model="dialog"
                                  @onSelect="addAdmin"
                                  :admin="true"></DlgSearchStudent>
            </div>
        </div>
        <div class="card-body py-0">
            <Loader :loading="loader"></Loader>
            <div class="table-responsive m-0">
                <table class="table table-bordered table-hover m-0 table-sm " v-if="!loader && laravelData">
                    <thead>
                    <tr class="align-middle">
                        <th>Administrador</th>
                        <th>Cedula</th>
                        <th>Correo</th>
                        <th>Rol</th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="p in laravelData.data" :key="p.id">
                        <td data-name="Admin">{{ p.person.surname }} {{ p.person.name }}</td>
                        <td data-name="Cedula">{{ p.person.dni }}</td>
                        <td data-name="Correo">{{ p.email }}</td>
                        <td data-name="rol">{{ p.roles.map(r => r['description']).join(',')}}</td>
                        <td class="text-right">
                            <button type="button" v-if="canDelete" @click="deleteAdmin(p)"
                                    class="btn btn-sm btn-outline-danger" >
                                <i class="fa fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                    <tr v-if="laravelData.data && laravelData.data.length <=0">
                        <td colspan="4" class="no-data" >No hay administradores para este evento</td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-body py-0 text-center">
            <pagination :data="laravelData" @pagination-change-page="getAdmins"/>
        </div>

    </div>
</template>

<script>
import Loader from "./_partials/Loader";
import DlgSearchStudent from "./_dialog/DlgSearchStudent";
export default {
    name: "AdminsEventComponent",
    components: {DlgSearchStudent, Loader},
    props: {
        event: {
            type: Number|String,
            default: null
        },
        canAdd: {
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
            laravelData: {},
            loader: false,
            dialog: false
        }
    },
    mounted() {
        this.getAdmins();
    },
    methods: {
        getAdmins(page = 1) {
            if (!this.event) return;
            this.loader = true;
            axios.get(`/events/admins/api/${this.event}?page=${page}`).then(res => {
                if (res.data.ok) {
                    this.laravelData = res.data.body
                }
            }).finally(() => this.loader = false);
        },
        addAdmin(p) {
            if (!this.canAdd) return;
            if (this.laravelData.data.find(e => e.id === p.id)) {
                this.$alert.err("Esta persona ya esta en la lista");
                return;
            }
            this.dialog = false;
            let self = this;
            this.$dialog
                .confirm({title: 'Agregar nuevo administrador', body: `¿Esta seguro que desea agregar a ${p.surname} ${p.name} de la lista de administradores?`}, {loader: true})
                .then(function(dialog) {

                    axios.post(`/events/admins/add`, {event_id: self.event, user_id: p.id}).then(res =>{
                        if (res.data.ok) {
                            self.laravelData.data.push({
                                person: {
                                    surname : p.surname,
                                    name: p.name,
                                    dni: p.dni,
                                    id: p.person_id
                                },
                                id: p.id,
                                email: p.email,
                                roles: p.roles
                            });
                            self.$alert.ok(res.data.message);
                        }
                    }).finally(() => {
                        dialog.close();
                    });
                })
        },
        deleteAdmin(p){
            if (!this.canDelete) return;
            let self = this;
            this.$dialog
                .confirm({title: 'Confirmar eliminación', body: `¿Esta seguro que desea eliminar a ${p.surname} de la lista de administradores?`}, {loader: true})
                .then(function(dialog) {
                    axios.delete(`/events/admins/${self.event}/${p.id}`).then(res =>{
                        if (res.data.ok) {
                            const index = self.laravelData.data.indexOf(p);
                            if (index>= 0) {
                                self.laravelData.data.splice(index, 1);
                            }
                            self.$alert.ok(res.data.message);
                        }
                    }).catch(err => self.$alert.err(err))
                        .finally(() => dialog.close());
                })
        }
    },
}
</script>

<style scoped>

</style>
