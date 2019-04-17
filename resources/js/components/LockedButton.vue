<template>
    <button class="btn btn-primary" @click="lock" v-if="!locked">Lock thread</button>
    <button class="btn btn-info" @click="unlock" v-else>Unlock thread</button>
</template>

<script>
    export default {
        props: ['locked'],
        methods: {
            lock() {
                axios
                    .patch(location.pathname + '/lock')
                    .then(res => {
                        flash(res.data);
                        this.locked = true;
                    })
                    .catch(error => flash(error.response.data, 'danger'))
            },
            unlock() {
                axios
                    .delete(location.pathname + '/unlock')
                    .then(res => {
                        flash(res.data);
                        this.locked = false;
                    })
                    .catch(error => flash(error.response.data, 'danger'))
            }
        },
    }
</script>

<style scoped>

</style>