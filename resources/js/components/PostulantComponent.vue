<template>
    <div class="card">
        <div class="card-header">
            <b><i class="fa fa-user"></i>Listado de postulantes</b>
        </div>
        <div class="card-body p-0">
            <Loader :loading="loader"></Loader>
            <div class="table-responsive table-bordered m-0">
                <table class="table table-hover m-0 table-sm " v-if="!loader && laravelData">
                    <thead>
                    <tr>
                        <th>Estudiante</th>
                        <th>Estado</th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="p in laravelData.data" :key="p.id">
                        <td>{{ p.surname }} {{ p.name }}</td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td colspan="3" class="text-center text-muted py-2" v-if="laravelData.data.length <=0">No hay postulantes para este curso</td>
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
    },
    data: () => ({
        loader: false,
        laravelData: {}
    }),
    created() {
        this.getPostulantes();
    },
    methods: {
        getPostulantes(page = 1) {
            if(!this.event) return;
            this.loader = true;
            axios.get(`/api/postulantes/${this.event}?page=${page}`, {
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            }).then((res) => {
                if (res.data) {
                    this.laravelData = res.data.data;
                }
            }).finally(() => this.loader = false);
        }
    },
}
</script>

