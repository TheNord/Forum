<template>
    <li class="nav-item dropdown" v-if="notifications.length">
        <a href="#" class="nav-link" data-toggle="dropdown">
            <i class="fa fa-bell"></i>
        </a>

        <div class="dropdown-menu" aria-labelledby="notifyDropdown">
            <a class="dropdown-item"
               v-for="notification in notifications"
               :href="notification.data.link"
               @click="read(notification)">
                {{ notification.data.message }}
            </a>
        </div>
    </li>
</template>

<script>
    export default {
        data() {
            return {
                notifications: false
            }
        },

        created() {
            axios
                .get('/notifications')
                .then(res => this.notifications = res.data)
                .catch(error => console.log(error))
        },

        methods: {
            read(notification) {
                axios
                    .delete(`/notifications/${notification.id}/read`)
                    .then(res => console.log(res))
                    .catch(error => console.log(error))
            }
        },
    }
</script>