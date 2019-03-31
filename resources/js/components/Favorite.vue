<template>
    <button type="submit" class="btn btn-default" :class="{ 'icn-liked': !isFavorited }" @click="toggle">
        <span class="fa fa-heart"></span>
        <span v-text="favoriteCount"></span>
        {{ reply.is_favorited}}
    </button>
</template>

<script>
    export default {
        props: ['reply'],
        data() {
            return {
                favoriteCount: this.reply.favorites_count,
                isFavorited: this.reply.isFavorited,
            }
        },
        computed: {
            endpoint() {
                return `/replies/${this.reply.id}/favorite`;
            }
        },
        methods: {
            toggle() {
                this.isFavorited ? this.destroy() : this.create();
            },
            create() {
                axios
                    .post(this.endpoint)
                    .then(res => {
                        this.favoriteCount++;
                        this.isFavorited = true;
                    })
                    .catch(error => flash(error.response.data, 'danger'))
            },
            destroy() {
                axios
                    .delete(this.endpoint)
                    .then(res => {
                        this.favoriteCount--;
                        this.isFavorited = false;
                    })
                    .catch(error => flash(error.response.data, 'danger'))
            }
        },
    }
</script>