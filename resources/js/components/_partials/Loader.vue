<template>
    <div class="text-center py-4 d-flex flex-column align-items-center" v-if="loading">
        <div class="spinner-border text-dark" role="status">
            <span class="sr-only">Cargando...</span>
        </div>
        <small class="text-muted d-block my-1">{{ text }}</small>
    </div>
</template>

<script>
export default {
    name: "Loader",
    props: {
        loading: {
            type: Boolean,
            default: false
        },
        text: {
            type: String,
            default: 'Cargando...'
        }
    },
}
</script>

<style scoped>

</style>
