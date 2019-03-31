<template>
    <button class="btn btn-success" @click="subscribe" v-if="!subscribed">Subscribe</button>
    <button class="btn btn-info" @click="unsubscribe" v-else>Unsubscribe</button>
</template>

<script>
    export default {
        props: ['active'],
        data() {
            return {
                subscribed: this.active
            }
        },
        methods: {
            subscribe() {
                axios
                    .post(location.pathname + '/subscribe')
                    .then(res => {
                        flash('Subscribed');
                        this.subscribed = true;
                    })
                    .catch(error => flash(error.response.data, 'danger'))
            },
            unsubscribe() {
                axios
                    .delete(location.pathname + '/unsubscribe')
                    .then(res => {
                        flash('Unsubscribed');
                        this.subscribed = false;
                    })
                    .catch(error => flash(error.response.data, 'danger'))
            }
        },
    }
</script>

<style scoped>

</style>