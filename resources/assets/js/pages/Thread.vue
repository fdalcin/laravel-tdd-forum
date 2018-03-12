<script>
import Replies from '../components/Replies.vue';
import SubscribeButton from '../components/SubscribeButton.vue';

export default {
    props: ['thread'],

    components: { SubscribeButton, Replies },

    data() {
        return {
            body: this.thread.body,
            title: this.thread.title,
            locked: this.thread.locked,
            repliesCount: this.thread.replies_count,
            editing: false,
            form: {}
        };
    },

    created() {
        this.resetForm();
    },

    methods: {
        toggleLock() {
            const uri = `/locked-threads/${this.thread.slug}`;

            axios[this.locked ? 'delete' : 'post'](uri).then(
                () => (this.locked = !this.locked)
            );
        },

        update() {
            const uri = `/threads/${this.thread.channel.slug}/${
                this.thread.slug
            }`;

            axios
                .patch(uri, this.form)
                .then(() => {
                    this.editing = false;

                    this.title = this.form.title;
                    this.body = this.form.body;

                    flash('Your thread has been updated.');
                })
                .catch(({ response }) =>
                    flash(response.data.message, 'danger')
                );
        },

        resetForm() {
            this.form = {
                body: this.thread.body,
                title: this.thread.title
            };

            this.editing = false;
        }
    }
};
</script>
