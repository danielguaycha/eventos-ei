<template>
    <div class="table-responsive">
        <Loader :loading="loader"></Loader>
        <table class="table table-hover table-sm table-bordered mb-0" v-if="!loader">
            <thead>
            <tr>
                <th>Estudiante</th>
                <th class="text-center">Nota /7</th>
                <th class="text-center">Nota /3</th>
                <th class="text-center">Total</th>
                <th></th>
            </tr>
            </thead>
            <tbody>
                <tr v-for="p in laravelData.data" :key="p.id">
                    <td data-name="Estudiante">{{ p.surname }} {{ p.name }}</td>
                    <td width="10%" class="text-center td-100" data-name="Nota /7">{{ p.nota_7 }}</td>
                    <td width="10%" class="text-center td-100" data-name="Nota /3">{{ p.nota_3 }}</td>
                    <td width="10%" class="text-center td-100" data-name="Total">
                        <b>
                            {{ p.nota_3 + p.nota_7 }}
                        </b>
                    </td>
                    <td width="1%" class="td-100" data-name="Estado">
                        <small v-if="p.nota_7 + p.nota_3 < 7" class="text-danger">Reprobado</small>
                        <small v-else class="text-success">Aprobado</small>
                    </td>
                </tr>
            </tbody>
        </table>
        <div class="text-center">
            <pagination :data="laravelData" @pagination-change-page="getNotas"/>
        </div>
    </div>
</template>

<script>
import Loader from "./Loader";
export default {
    name: "TableNotas",
    components: {Loader},
    props: {
        event: {
            type: Number|String,
            required: true
        },
    },
    data() {
        return {
            loader: false,
            laravelData: {}
        }
    },
    mounted() {
        this.getNotas();
    },
    methods: {
        getNotas(page=1) {
            if (!this.event) return;
            this.loader = true;
            axios.get(`/notas/${this.event}?page=${page}`).then(res => {
                if (res.data.ok) {
                    this.laravelData = res.data.body;
                }
            }).finally(() => this.loader = false);
        }
    },
}
</script>

<style scoped>

</style>
