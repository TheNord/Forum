<template>
    <div :id="'reply-'+id" class="card mt-4">
        <div class="card-header" :class="attributes.isBest ? 'best-answer' : ''">

            <img :src="attributes.owner.avatar" alt="user_avatar" height="25" width="25" class="mr-1">

            <a :href="'/profile/'+attributes.owner.name+'/show'"
            v-text="attributes.owner.name">
            </a>
            said {{ attributes.created_at }}...

            <div class="float-right" v-if="signedIn">
                <best-reply :reply="attributes" v-if="authorize('canMarkBest', attributes.thread_creator_id)"></best-reply>
                <favorite :reply="attributes"></favorite>
            </div>
        </div>
        <div class="card-body">
            <article>
                <div class="body">
                    <div v-if="editing">
                        <div class="form-group">
                            <textarea name="body" class="form-control" rows="5" v-model="attributes.body" :class="{'is-invalid': errors.body}"></textarea>
                            <span v-if="errors.body" class="invalid-feedback"><strong>{{ errors.body[0] }}</strong></span>
                        </div>

                        <button class="btn btn-sm btn-primary" @click="update">Update</button>
                        <button class="btn btn-sm btn-link" @click="editing = false">Cancel</button>
                    </div>
                    <div v-else v-html="attributes.body"></div>
                </div>
            </article>

            <div v-if="authorize('updateReply', reply)">
                <hr/>
                <div class="float-right btn-group">
                    <button class="btn-icn mr-3" @click="editing = true"><i class="fa fa-pencil-alt icn-edit "></i></button>
                    <button class="btn-icn" @click="destroy"><i class="far fa-trash-alt icn-delete"></i></button>
                </div>
                </div>
            </div>
    </div>
</template>

<script>
    import Favorite from './Favorite';
    import BestReply from './BestReply';

    export default {
        props: ['attributes'],
        data() {
            return {
                editing: false,
                id: this.attributes.id,
                errors: [],
                reply: this.attributes
            }
        },
        created() {
            events.$on('markBest', (reply) => {
                if (this.attributes.id === reply) {
                    this.attributes.isBest = true;
                }
            });

            events.$on('unMarkBest', (reply) => {
                if (this.attributes.id === reply) {
                    this.attributes.isBest = false;
                }
            });
        },
        methods: {
            update() {
                axios
                    .put(`/reply/${this.attributes.id}`, {
                        body: this.attributes.body
                    })
                    .then(res => {
                        flash(res.data);
                        this.editing = false;
                        this.errors = [];
                    })
                    .catch(error => this.errors = error.response.data.errors)
            },
            destroy() {
                axios
                    .delete(`/reply/${this.attributes.id}`)
                    .then(res => {
                        $(this.$el).fadeOut(300);
                        flash(res.data);
                        this.$emit('deleted', this.attributes.id)
                    })
                    .catch(error => console.log(error))
            },
        },
        components: {
            Favorite,
            BestReply
        }
    }
</script>

<style>
    .best-answer {
        background-color: #bae2bc;
        color: #fff;
    }

    .best-answer a {
        color: #5371af;
    }
</style>