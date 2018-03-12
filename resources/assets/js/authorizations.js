const user = window.App.user;

module.exports = {
    owns: (model, prop = 'user_id') => model[prop] === user.id,

    isAdmin: () => ['JohnDoe', 'JaneDoe'].includes(user.name)
};
