fsigned
<template>
    <div>
        <div v-if="signedIn">
            <div class="form-group">
                <wysiwyg name="body"
                         v-model="body"
                         placeholder="Have something to say?"
                         :shouldClear="completed"
                ></wysiwyg>
            </div>

            <button type="submit"
                    class="btn btn-primary"
                    @click="reply">Post
            </button>
        </div>

        <p class="justify-content-center text-center mt-4" v-else>
            Please <a href="/login">sing in</a> to participate in this discussion.
        </p>
    </div>
</template>

<script>
    import 'jquery.caret';
    import 'at.js';

    export default {
        data() {
            return {
                body: '',
                completed: false
            };
        },

        mounted() {
            $('#body').atwho({
                at: '@',
                delay: 750,
                callbacks: {
                    remoteFilter: (query, callback) => {
                        axios.get('/api/users', {params: {name: query}}).then(({data}) => callback(data));
                    }
                }
            });
        },

        methods: {
            reply() {
                axios.post(location.pathname + '/replies', {body: this.body})
                        .then(({data}) => {
                            this.body = '';
                            this.completed = true;

                            flash('Your reply has been posted.');

                            this.$emit('created', data);
                        })
                        .catch(({response}) => flash(response.data, 'danger'));
            }
        }
    };
</script>
