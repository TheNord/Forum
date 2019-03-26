<script>
    import Favorite from './Favorite';

    export default {
        props: ['attributes'],
        data() {
            return {
                editing: false,
                body: this.attributes.body
            }
        },
        methods: {
            update() {
                axios
                    .put(`/reply/${this.attributes.id}`, {
                        body: this.body
                    })
                    .then(res => {
                        flash(res.data);
                        this.editing = false;
                    })
                    .catch(error => console.log(error))
            },
            destroy() {
                axios
                    .delete(`/reply/${this.attributes.id}`)
                    .then(res => {
                        $(this.$el).fadeOut(300);
                        flash(res.data); 
                    })
                    .catch(error => console.log(error))
            }
        },
        components: {
            Favorite
        }
    }
</script>