<template>
    <div class="card">
        <div class="card-header">
            <div >
                <b><i class="fa fa-user"></i>Calificaciones</b>
            </div>
            <div class="d-flex align-items-center" v-if="laravelData.data && laravelData.data.length > 0 ">
                <div class="spinner-border text-dark spinner-border-sm mr-2" role="status" v-if="loaderSave"></div>

                <button class="btn btn-sm btn-success" @click="saveNotas" :disabled="loaderSave">
                    <i class="fa fa-save mr-1"></i>{{ loaderSave ? 'Guardando...' : 'Guardar' }}</button>

                <DlgConfirmNotas v-model="dialog" :event="event" @onConfirm="confirmNotas" :disabled="loaderSave"/>
            </div>
        </div>
        <div class="card-body p-0">
            <Loader :loading="loader"></Loader>
            <div class="table-responsive table-bordered m-0">
                <table class="table table-hover m-0 table-sm " v-if="!loader && laravelData">
                    <thead>
                    <tr class="align-middle">
                        <th>Estudiante</th>
                        <th>Nota /7</th>
                        <th>Nota /3</th>
                        <th>Promedio</th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="p in laravelData.data" :key="p.id">
                        <td>{{ p.surname }} {{ p.name }}</td>
                        <td class="input-nota">
                            <vue-numeric
                                :min="0" v-bind:max="7"
                                :precision="2"
                                class="form-control form-control-sm text-center"
                                :empty-value="0"
                                decimal-separator="."
                                v-model="p.nota_7" />
                        </td>
                        <td class="input-nota">
                            <vue-numeric
                                :min="0" v-bind:max="3"
                                :precision="2"
                                class="form-control form-control-sm text-center"
                                :empty-value="0"
                                decimal-separator="."
                                v-model="p.nota_3" />
                        </td>
                        <td class="input-nota">
                            <input type="text"
                                   :disabled="true"
                                   :value="(p.nota_7 + p.nota_3)"
                                   readonly class="form-control form-control-sm">
                        </td>
                        <td width="1%">
                            <small v-if="p.nota_7 + p.nota_3 < 7" class="text-danger">Reprobado</small>
                            <small v-else class="text-success">Aprobado</small>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="5" class="text-center text-muted py-2" v-if="laravelData.data && laravelData.data.length <=0">No hay estudiantes en este evento</td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-body py-0 text-center">
            <pagination :data="laravelData" @pagination-change-page="getNotas"/>
        </div>
    </div>
</template>

<script>
import Loader from "./_partials/Loader";
import DlgConfirmNotas from "./_dialog/DlgConfirmNotas";
export default {
    name: "NotasComponent",
    components: {DlgConfirmNotas, Loader},
    props: {
        event: {
            type: Number|String,
            default: null
        },
    },
    data() {
        return {
            loader: false,
            loaderSave: false,
            laravelData: {},
            dialog : false
        }
    },
    mounted() {
        this.getNotas();
    },
    methods: {
        getNotas(page = 1) {
            if (!this.event) return;
            this.loader = true;
            axios.get(`/notas/${this.event}?page=${page}`).then(res => {
                if (res.data.ok) {
                    this.laravelData = res.data.body;
                }
            }).finally(() => this.loader = false);
        },
        saveNotas(){
            let form = [];

            this.laravelData.data.forEach(e => {
                if (e.nota_7 > 0 || e.nota_3 > 0) {
                    form.push({
                        id: e.id, nota_7: e.nota_7, nota_3: e.nota_3
                    });
                }
            });

            if (form.length <= 0) return;
            this.loaderSave = true;
            axios.post(`/events/notas/save/${this.event}`, {notas: form}).then(res => {
                if (res.data.ok) {
                    this.$alert.ok(res.data.message);
                }
            }).catch(err => this.$alert.err(err))
            .finally(() => this.loaderSave = false);
        },
        confirmNotas() {
            let self = this;
            this.$dialog
                .confirm({title: 'Confirmar envío de Notas', body: `Una vez enviadas las notas no se permiten modificaciones, si lo que desea es solo guardar el avance use el botón "Guardar", por el contrario presione "Confirmar"`}, {loader: true})
                .then(function(dialog) {
                    axios.post(`/events/notas/finish/${self.event}`).then(res =>{
                        if (res.data.ok) {
                            self.$alert.ok(res.data.message);
                            window.location.reload();
                        }
                    }).catch(err => self.$alert.err(err))
                        .finally(() => dialog.close());
                })
        }
    },
}
</script>

<style scoped>
    .input-nota {
        width: 1%;
    }
    .input-nota input{
        width: 70px;
        margin: 0 5px;
    }
</style>
