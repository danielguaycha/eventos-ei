<template>
    <div class="card">
        <div class="card-header">
            <div >
                <b><i class="fa fa-user"></i>Listado de postulantes</b>
                <span v-if="laravelData.data">({{ laravelData.data.length }})</span>
            </div>
            <div class="flex-grow-0" v-if="form.postulantes.length > 0 && canAccept">
                <button @click="setStatusAll" class="btn btn-sm btn-primary mr-1"><i class="fa fa-check"></i> Aprobar ({{ form.postulantes.length}})</button>
            </div>
        </div>
        <div class="card-body p-0">
            <Loader :loading="loader"></Loader>
            <slot></slot>
            <div class="table-responsive m-0">
                <table class="table table-bordered table-hover m-0 table-sm " v-if="!loader && laravelData">
                    <thead>
                    <tr class="align-middle">
                        <th class="text-center"></th>
                        <th>Estudiante</th>
                        <th>Estado</th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="p in laravelData.data" :key="p.id">
                        <td width="1%" class="text-center td-radio">
                            <label class="form-check-label" v-if="p.status === 0">
                                <input class="form-check-input" type="checkbox"
                                       :value="p.id"
                                       v-model.number="form.postulantes"
                                       :aria-label="'Seleccionar '+p.name">
                            </label>
                        </td>
                        <td data-name="Estudiante">{{ p.surname }} {{ p.name }}</td>
                        <td data-name="Estado">
                            <small class="text-success" v-if="p.status === 1"><b>Aprobado</b></small>
                            <small class="text-danger" v-if="p.status === 0"><b>No aprobado</b></small>
                        </td>
                        <td class="text-right">
                            <button type="button" :disabled="loaderStatus" v-if="canAccept"
                                    @click="setStatus(p)"
                                    :class="`btn btn-outline-${(p.status === 0 ? 'primary': 'danger')} btn-sm`" >
                                <i :class="`fa fa-${p.status === 0 ? 'check' : 'times'}`"></i>
                            </button>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="4" class="no-data" v-if="laravelData.data.length <=0">No hay postulantes para este evento</td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-body py-0 text-center">
            <pagination :data="laravelData" @pagination-change-page="getPostulantes"/>
        </div>
    </div>
</template>

<script>
import Loader from "./_partials/Loader";
export default {
    name: "Postulantes",
    components: {Loader},
    props: {
        event: {
            type: Number|String,
            default: null
        },
        canAccept: {
            type: Boolean,
            default: false
        }
    },
    data: () => ({
        loader: false,
        loaderStatus: false,
        laravelData: {},
        form: {
            postulantes: []
        }
    }),
    created() {
        this.getPostulantes();
    },
    methods: {
        getPostulantes(page = 1) {
            if(!this.event) return;
            this.loader = true;
            axios.get(`/postulantes/listar/${this.event}?page=${page}`).then((res) => {
                if (res.data) {
                    this.laravelData = res.data.body;
                }
            }).finally(() => this.loader = false);
        },
        setStatusAll(){
            if (!this.canAccept) return;

            this.loaderStatus = true;
            axios.put(`/postulantes/accept/all`, this.form).then(res => {
                if (res.data.ok){
                    this.laravelData.data.forEach(e => {
                        this.form.postulantes.forEach(p => {
                            if (e.id === p) {
                                e.status = 1;
                            }
                        })
                    });
                    this.form.postulantes = [];
                    this.$alert.ok(res.data.message);
                }
            }).catch(err => {

            }).finally(() => this.loaderStatus = false);
        },
        setStatus(p) {
            if (!this.canAccept) return;
            this.loaderStatus=true;
              axios.get('/postulantes/accept/'+p.id).then(res => {
                  if (res.data.ok) {
                      p.status = res.data.body;
                      this.$alert.ok(res.data.message);
                  }
              }).catch(err => {
                    this.$alert.err("Error al aprobar la postulación, contacte con soporte");
              }).finally(() => this.loaderStatus = false);
        },
        selectAll($e) {
            console.log($e);
        }
    },
}
</script>

