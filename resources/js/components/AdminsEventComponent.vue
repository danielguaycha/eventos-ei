<template>
    <div class="card">
        <div class="card-header">
            <div >
                <b><i class="fa fa-user"></i>Listado de administradores ({{ laravelData.data ?  laravelData.data.length : 0}})</b>
            </div>
            <div v-if="canAdd">
                <DlgSearchStudent v-model="dialog"
                                  :admin="true"
                                  @onSelect="addAdmin"></DlgSearchStudent>
            </div>
        </div>
        <div class="card-body py-0">
            <Loader :loading="loader"></Loader>
            <div class="table-responsive m-0">
                <table v-if="!loader && laravelData" class="table table-bordered table-hover m-0 table-sm ">
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
                            <button v-if="canDelete" class="btn btn-sm btn-outline-danger" type="button"
                                    @click="deleteAdmin(p)">
                                <i class="fa fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                    <tr v-if="laravelData.data && laravelData.data.length <=0">
                        <td class="no-data" colspan="4">No hay administradores para este evento</td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-body py-0 text-center">
            <pagination :data="laravelData" @pagination-change-page="getAdmins"/>
        </div>
        <dlg-confirm ref="confirm"></dlg-confirm>
    </div>
</template>

<script>
import Loader from "./_partials/Loader";
import DlgSearchStudent from "./_dialog/DlgSearchStudent";
import DlgConfirm from "./_dialog/DlgConfirm";

export default {
    name: "AdminsEventComponent",
    components: {DlgConfirm, DlgSearchStudent, Loader},
    props: {
        event: {
            type: Number | String,
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
        async addAdmin(p) {
            if (!this.canAdd) return;
            if (this.laravelData.data.find(e => e.id === p.id)) {
                this.$alert.err("Esta persona ya esta en la lista");
                return;
            }
            this.dialog = false;
            let self = this;

            if (!await this.$refs.confirm.open('Agregar Administrador',
                `¿Esta seguro que desea agregar a ${p.surname} ${p.name} de la lista de administradores?`, {loader: true})) {
                return;
            }

            axios.post(`/events/admins/add`, {event_id: self.event, user_id: p.id}).then(res => {
                if (res.data.ok) {
                    self.laravelData.data.push({
                        person: {
                            surname: p.surname,
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
                this.$refs.confirm.close();
            });

        },
        async deleteAdmin(p) {
            if (!this.canDelete) return;
            let self = this;
            if (!await this.$refs.confirm.open('Confirmar Eliminación',
                `¿Esta seguro que desea eliminar a ${p.person.surname} ${p.person.name}  de la lista de administradores?`,
                {loader: true})) {
                return;
            }

            axios.delete(`/events/admins/${self.event}/${p.id}`).then(res => {
                if (res.data.ok) {
                    const index = self.laravelData.data.indexOf(p);
                    if (index >= 0) {
                        self.laravelData.data.splice(index, 1);
                    }
                    self.$alert.ok(res.data.message);
                }
            }).catch(err => self.$alert.err(err))
                .finally(() => this.$refs.confirm.close());
        }
    },
}
</script>

<style scoped>

</style>
