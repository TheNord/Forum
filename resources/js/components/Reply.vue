<template>
    <div :id="'reply-'+id" class="card mt-4">
        <div class="card-header">
            <a :href="'/profile/'+attributes.owner.name"
            v-text="attributes.owner.name">
            </a>
            said {{ attributes.created_at }}...

            <div class="float-right" v-if="signedIn">
                <favorite :reply="attributes"></favorite>
            </div>
        </div>
        <div class="card-body">
            <article>
                <div class="body">
                    <div v-if="editing">
                        <div class="form-group">
                            <textarea name="body" class="form-control" rows="5" v-model="body"></textarea>
                        </div>

                        <button class="btn btn-sm btn-primary" @click="update">Update</button>
                        <button class="btn btn-sm btn-link" @click="editing = false">Cancel</button>
                    </div>
                    <div v-else v-text="body"></div>
                </div>
            </article>

            <div v-if="canUpdate">
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

    export default {
        props: ['attributes'],
        data() {
            return {
                editing: false,
                id: this.attributes.id,
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
                        this.$emit('deleted', this.attributes.id)
                    })
                    .catch(error => console.log(error))
            }
        },
        computed: {
            signedIn() {
                return window.App.signedIn;
            },
            canUpdate() {
                return this.authorize(user => this.attributes.user_id === user.id);
            }
        },
        components: {
            Favorite
        }
    }
</script>