<script>
    import Replies from '../components/Replies';
    import SubscribeButton from '../components/SubscribeButton';
    import LockedButton from '../components/LockedButton';

    export default {
        props: ['initialRepliesCount'],
        data() {
            return {
                repliesCount: this.initialRepliesCount
            }
        },
        components: {
            Replies,
            SubscribeButton,
            LockedButton
        }
    }
</script>