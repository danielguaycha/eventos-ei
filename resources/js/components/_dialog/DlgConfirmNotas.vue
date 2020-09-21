<template>
    <div>
        <div>
            <button class="btn btn-sm btn-primary" @click="show = true" :disabled="disabled">
                <i class="fa fa-check-circle mr-1"></i>Enviar notas</button>
        </div>
        <Dialog v-model="show">
            <div class="dlg-header">
                <b>Notas | </b>
                <small class="ml-2"><i class="fa fa-exclamation-circle mr-1"></i>Verifique que todas las notas son correctas antes de enviar
            </small>
            </div>
            <div class="dlg-body">
                <TableNotas :event="event" v-if="show" />
            </div>
            <div class="dlg-footer text-right">
                <button class="btn btn-danger" @click="show = false">Cancelar</button>
                <button @click="emit" class="btn btn-primary"><i class="fa fa-save mr-1"></i>Enviar Notas</button>
            </div>
        </Dialog>
    </div>
</template>

<script>
import Dialog from "../_partials/Dialog";
import TableNotas from "../_partials/TableNotas";
export default {
    components: {TableNotas, Dialog},
    props: {
        value: Boolean,
        event: String|Number,
        disabled: {
            type: Boolean,
            default: false
        }
    },
    name: "DlgConfirmNotas",
    methods: {
      emit() {
          this.show = false;
          this.$emit('onConfirm', true);
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
}
</script>

<style scoped>

</style>
