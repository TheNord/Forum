let user = window.App.user;

let authorizations = {
    updateReply(reply) {
        return reply.user_id === user.id;
    },
    canMarkBest(creator) {
        return creator === user.id;
    }
};

module.exports = authorizations;