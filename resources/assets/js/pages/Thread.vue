<script>
import Replies from '../components/Replies.vue';
import SubscribeButton from '../components/SubscribeButton.vue';

export default {
    props: ['thread'],

    components: { SubscribeButton, Replies },

    data() {
        return {
            repliesCount: this.thread.replies_count,
            locked: this.thread.locked,
            editing: false,
            body: this.thread.body
        };
    },

    methods: {
        toggleLock() {
            axios[this.locked ? 'delete' : 'post'](
                '/locked-threads/' + this.thread.slug
            ).then(() => (this.locked = !this.locked));
        },

        cancel() {
            this.body = this.thread.body;

            this.editing = false;
        },

        update() {}
    }
};
</script>
