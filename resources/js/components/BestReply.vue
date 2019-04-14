<template>
        <button class="btn btn-sm btn-success" @click="toggle" v-text="text" v-if="reply.id === reply.bestReplyId || !reply.bestReplyId"></button>
</template>

<script>
    export default {
        props: ['reply'],
        data() {
            return {
                isBest: this.reply.isBest,
            }
        },
        created() {
            events.$on('markBest', (reply) => {
                this.reply.bestReplyId = reply;
            });

            events.$on('unMarkBest', () => {
                this.reply.bestReplyId = null;
            });
        },
        computed: {
            endpoint() {
                return `/replies/${this.reply.id}/best`;
            },
            text() {
                return this.isBest ? 'Un mark as Best' : 'Mark as Best';
            }
        },
        methods: {
            toggle() {
                this.isBest ? this.unMark() : this.mark();
            },
            mark() {
                axios
                    .post(this.endpoint)
                    .then(res => {
                        this.isBest = true;
                        events.$emit('markBest', this.reply.id);
                        flash(res.data);
                    })
                    .catch(error => flash(error.response.data, 'danger'))
            },
            unMark() {
                axios
                    .delete(this.endpoint)
                    .then(res => {
                        this.isBest = false;
                        events.$emit('unMarkBest', this.reply.id);
                        flash(res.data);
                    })
                    .catch(error => flash(error.response.data, 'danger'))
            }
        },
    }
</script>