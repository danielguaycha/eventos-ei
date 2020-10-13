<template>
    <Dialog v-model="show" alert>
        <div class="dlg-header">
            <b>{{ title }}</b>
        </div>
        <div class="dlg-body">
            {{ message }}
        </div>
        <div class="dlg-footer text-right">
            <button :disabled="loader" class="btn btn-outline-danger mr-1" @click="cancel">Cancelar</button>
            <button :disabled="loader" class="btn btn-primary" @click="agree">
                <span v-if="loader" class="spinner-border spinner-border-sm"></span>
                Confirmar
            </button>
        </div>
    </Dialog>
</template>

<script>
import Dialog from "../_partials/Dialog";

export default {
    name: "DlgConfirm",
    components: {Dialog},
    data() {
        return {
            resolve: null,
            reject: null,
            message: null,
            title: null,
            options: {
                color: 'primary',
                loader: false
            },
            show: false,
            loader: false
        }
    },
    methods: {
        open(title = 'Confirmar', message = '¿Está seguro de que desea realizar esta acción?', options) {
            this.show = true;
            this.title = title;
            this.message = message;
            this.options = Object.assign(this.options, options);
            return new Promise((resolve, reject) => {
                this.resolve = resolve;
                this.reject = reject
            })
        },
        agree() {
            this.resolve(true);
            if (!this.options.loader)
                this.show = false;
            else
                this.loader = true;
        },
        cancel() {
            this.resolve(false);
            this.show = false;
            this.options.loader = false;
            this.loader = false;
        },
        close() {
            this.resolve(false);
            this.show = false;
            this.options.loader = false;
            this.loader = false;
        }
    },
}
</script>

<style scoped>

</style>
