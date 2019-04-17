<template>
    <div>
        <span v-if="thread.locked" class="mt-15 text-danger">This thread has been locked.</span>

        <div v-if="signedIn">
            <form method="post" @submit.prevent="send">
                <div class="form-group">
                    <label for="body"></label>
                    <textarea v-model="body" id="body"
                              class="form-control" :class="{'is-invalid': errors.body}"
                              rows="6" placeholder="Have something to say?"
                              :disabled="thread.locked"
                    >{{ body }}</textarea>

                    <span v-if="errors.body" class="invalid-feedback"><strong>{{ errors.body[0] }}</strong></span>
                </div>

                <button type="submit" class="btn btn-success" :disabled="thread.locked">Post</button>
            </form>
        </div>
        <div v-else>
            <p>Please <a href="/login">sign in</a> to participate in this discussion.</p>
        </div>

    </div>
</template>

<script>
    import 'at.js';
    import 'jquery.caret';

    export default {
        props: ['data', 'channel'],
        data() {
            return {
                body: '',
                errors: [],
                thread: this.data,
                endpoint: location.pathname + '/replies'
            }
        },
        mounted() {
            $('#body').atwho({
                at: "@",
                delay: 200,
                callbacks: {
                    remoteFilter: function(query, callback) {
                        axios
                            .get('/api/users', {params: {
                                name: query
                                }
                            })
                            .then(res => callback(res.data))
                            .catch(error => console.log(error))
                    }
                }
            });
        },
        methods: {
            send() {
                axios
                    .post(this.endpoint, {body: this.body})
                    .then(res => {
                        this.body = '';
                        flash('Your reply was added');
                        this.$emit('new-reply', res.data);
                    })
                    .catch(error => {
                        flash(error.response.data, 'danger');
                        this.errors = error.response.data.errors;
                    })
            }
        },
    }
</script>