<template>
    <div>
        <div v-if="signedIn">
            <form method="post" @submit.prevent="send">
                <div class="form-group">
                    <label for="body"></label>
                    <textarea v-model="body" id="body"
                              class="form-control" :class="{'is-invalid': errors.body}"
                              rows="6" placeholder="Have something to say?">{{ body }}</textarea>

                    <span v-if="errors.body" class="invalid-feedback"><strong>{{ errors.body }}</strong></span>
                </div>

                <button type="submit" class="btn btn-success">Post</button>
            </form>
        </div>
        <div v-else>
            <p>Please <a href="/login">sign in</a> to participate in this discussion.</p>
        </div>
    </div>
</template>

<script>
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
        computed: {
            signedIn() {
                return window.App.signedIn;
            },
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
                    .catch(error => console.log(error.response.data))
            }
        },
    }
</script>