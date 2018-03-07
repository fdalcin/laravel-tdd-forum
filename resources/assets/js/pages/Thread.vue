<script>
import Replies from '../components/Replies.vue';
import SubscribeButton from '../components/SubscribeButton.vue';

export default {
    props: ['initialRepliesCount'],

    components: { SubscribeButton, Replies },

    data() {
        return {
            repliesCount: this.initialRepliesCount
        };
    }
};
</script>
