<template>
    <transition v-if="show" name="modal">
        <div class="modal-mask">
            <div class="modal-wrapper">
                <div v-if="!alert" class="modal-container">
                    <slot/>
                </div>
                <div v-else class="modal-container-confirm">
                    <slot/>
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
        alert: {
            type: Boolean,
            default: false
        }
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
