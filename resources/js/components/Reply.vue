<script>
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
            }
        },
    }
</script>