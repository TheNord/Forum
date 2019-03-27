<template>
    <div>
        <div v-for="(reply, index) in items">
            <reply :attributes="reply" @deleted="remove(index)"></reply>
        </div>
    </div>
</template>

<script>
    import Reply from './Reply';

    export default {
        props: ['data'],
        data() {
            return {
                items: this.data
            }
        },
        methods: {
            remove(index) {
                this.$emit('removed');
            }
        },
        components: {
            Reply
        }
    }
</script>