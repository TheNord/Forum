<script>
    import Replies from '../components/Replies';
    import SubscribeButton from '../components/SubscribeButton';
    import LockedButton from '../components/LockedButton';

    export default {
        props: ['initialRepliesCount', 'threadBody'],
        data() {
            return {
                repliesCount: this.initialRepliesCount,
                editing: false
            }
        },
        methods: {
            editThread() {
                this.editing = true;
            },
            updateThread() {
                axios
                    .put(location.pathname, {body: this.threadBody})
                    .then(res => {
                        flash(res.data);
                        this.editing = false;
                    })
                    .catch(error => flash(error.response.data, 'danger'));

                this.editing = false;
            }
        },
        components: {
            Replies,
            SubscribeButton,
            LockedButton
        }
    }
</script>