<template>
    <div>
        <div v-for="(reply, index) in items">
            <reply :attributes="reply" @deleted="remove(index)"></reply>
        </div>

        <div class="mt-4">
            <h2>New reply</h2>
            <new-reply :data="thread" :channel="thread.channel" @new-reply="newReply"></new-reply>
        </div>
    </div>
</template>

<script>
    import Reply from './Reply';
    import NewReply from '../components/NewReply';

    export default {
        props: ['channel', 'thread'],
        data() {
            return {
                items: this.data
            }
        },
        created() {
            this.getReplies();
        },
        methods: {
            remove(index) {
                this.$emit('removed');
            },
            newReply(reply) {
                this.items.push(reply);
            },
            getReplies() {
                axios
                    .get(`/thread/${this.thread.id}/replies`)
                    .then(res => this.items = res.data)
                    .catch(error => console.log(error))
            }
        },
        components: {
            Reply,
            NewReply
        }
    }
</script>