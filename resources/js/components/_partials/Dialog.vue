<template>
    <transition name="modal" v-if="show">
        <div class="modal-mask">
            <div class="modal-wrapper">
                <div class="modal-container">
                    <slot />
                </div>
            </div>
        </div>
    </transition>
</template>

<script>
export default {
    name: "Dialog",
    props: {
        value: Boolean,
    },
    methods: {
        close() {
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
    }
}
</script>

<style scoped>

</style>
