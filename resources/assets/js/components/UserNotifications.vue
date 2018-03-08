<template>
    <li class="nav-item dropdown" v-if="notifications.length">
        <a id="navbarDropdown"
           href="#"
           class="nav-link"
           role="button"
           data-toggle="dropdown"
           aria-haspopup="true"
           aria-expanded="false">
            <span class="fa fa-bell"></span>
        </a>

        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
            <a v-for="notification in notifications"
               :href="notification.data.link"
               class="dropdown-item"
               v-text="notification.data.message"
               @click="markAsRead(notification)"
            ></a>
        </div>
    </li>
</template>

<script>
    export default {
        data() {
            return {
                notifications: false
            };
        },

        created() {
            axios.get('/profiles/' + window.App.user.name + '/notifications')
                    .then(({data}) => this.notifications = data);
        },

        methods: {
            markAsRead(notification) {
                axios.delete('/profiles/' + window.App.user.name + '/notifications/' + notification.id);
            }
        }
    }
</script>