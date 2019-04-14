<template>
    <div>
        <div v-for="(reply, index) in items">
            <reply :attributes="reply" @deleted="remove(index)"></reply>
        </div>

        <paginator :dataSet="paginate" @update="changePage"></paginator>

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
                items: null,
                paginate: null,
                url: `/thread/${this.thread.slug}/replies`
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
            changePage(page) {
                this.url = `/thread/${this.thread.slug}/replies?page=` + page;
                this.getReplies();
                window.scroll(0, 0);
            },
            getReplies() {
                axios
                    .get(this.url)
                    .then(res => {
                        this.items = res.data.replies;
                        this.paginate = res.data.paginate
                    })
                    .catch(error => console.log(error))
            }
        },
        components: {
            Reply,
            NewReply
        }
    }
</script>