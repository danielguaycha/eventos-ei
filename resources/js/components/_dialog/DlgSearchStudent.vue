<template>
    <div>
        <div>
            <button class="btn btn-sm btn-primary" @click="show = true; getStudents();">
                <i class="fa fa-plus"></i> Agregar</button>
        </div>
        <Dialog v-model="show">
            <div class="dlg-header">
                <b class="text-muted">Buscar</b>
                <form @submit.prevent="getStudents">
                    <input type="text" v-model="search"
                           class="form-control" name="search" id="search" placeholder="Ingrese apellido o numero de cedula">
                    <button v-if="this.search" @click="search = ''; getStudents()"
                            type="button" class="btn"><i class="fa fa-times"></i></button>
                </form>
            </div>
            <div class="dlg-body">
                <Loader :loading="loader"/>
                <table class="table table-bordered table-hover" v-if="!loader">
                    <thead>
                    <tr>
                        <th>Nombres</th>
                        <th>Cedula</th>
                        <th v-if="admin">Rol</th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="p in students" :key="p.id" class="tr-hover" @dblclick="emit(p)">
                        <td data-name="Nombres">{{ p.surname }} {{ p.name }}</td>

                        <td data-name="Cedula"> {{ p.dni}} </td>
                        <td data-name="Rol" v-if="admin">
                            <small v-if="p.roles">
                                {{ p.roles.map(r => r['description']).join(',')}}
                            </small>
                            <small v-else>Desconocido</small>
                        </td>
                        <td class="text-right">
                            <button @click="emit(p)"
                                    class="btn btn-sm btn-primary"><i class="fa fa-plus"></i></button>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
            <div class="dlg-footer text-right">
                <button class="btn btn-outline-danger" @click="show = false">Cancelar</button>
            </div>
        </Dialog>
    </div>
</template>

<script>
import Dialog from "../_partials/Dialog";
import Loader from "../_partials/Loader";
export default {
name: "DlgSearchStudent",
    props: {
        value: Boolean,
        admin: {
            type: Boolean,
            default: false
        },
        autoClose: {
            type: Boolean,
            default: false
        }
    },
    data() {
        return {
            loader: false,
            students: [],
            search: ''
        }
    },
    mounted() {
        //this.getStudents()
    },
    methods: {
        getStudents() {
            this.loader = true;
            let url = `/user/students/search?search=${this.search}`;
            if (this.admin) url+= `&admins=true`;
            axios.get(url).then(res => {
                if (res.data.ok) {
                    this.students = res.data.body;
                }
            }).finally(() => this.loader = false);
        },
        emit(selectedPerson){
            this.$emit('onSelect', selectedPerson);
            if (this.autoClose)
                this.show = false;
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
        }
    },
    components: {Loader, Dialog}
}
</script>

<style scoped>

</style>
