<template>
    <button :class="getIcon" :disabled="loader" @click="changeVisibility">
        <i v-if="!loader" :class="`fa fa-${getIcon}`"></i>
        <span v-if="loader" class="spinner-border text-dark" role="status"></span>
    </button>
</template>

<script>
export default {
    name: "BtnVisibleEvent",
    props: {
        event: {
            type: Number,
            required: true
        },
        visible: {
            type: Number,
            required: true
        }
    },
    data() {
        return {
            isVisible: false,
            loader: false
        }
    },
    created() {
        this.isVisible = this.visible === 1;
    },
    methods: {
        changeVisibility() {
            this.loader = true;
            axios.get(`/events/visibility/${this.event}`).then(res => {
                if (res.data.ok) {
                    this.isVisible = res.data.data === 1;
                    this.$alert.ok(res.data.message);
                }
            })
                .catch(err => this.$alert.err(err))
                .finally(() => this.loader = false);
        }
    },
    computed: {
        getIcon() {
            if (this.isVisible === true) {
                return 'eye';
            } else {
                return 'eye-slash';
            }
        }
    }
}
</script>

<style scoped>

</style>
