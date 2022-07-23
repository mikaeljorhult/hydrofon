export default () => ({
    messages: [],

    counter: 0,

    base: {
        ['x-on:notify.window'] (event) {
            let message = {
                id: ++this.counter,
                title: event.detail.title,
                body: event.detail.body,
                level: event.detail.level ?? 'success',
                visible: false,
            };

            this.messages.push(message);

            // Show message after it has been added to array.
            setTimeout(() => {
                this.show(message)
            }, 10);

            // Hide message automatically after 4 seconds.
            setTimeout(() => {
                this.hide(message)
            }, 4000);
        },
    },

    show (message) {
        this.messages[this.getMessageIndex(message)].visible = true;
    },

    hide (message) {
        const index = this.getMessageIndex(message);

        if (index > -1) {
            this.messages[index].visible = false;

            // Remove message once hidden.
            setTimeout(() => {
                this.messages.splice(index, 1);
            }, 500);
        }
    },

    getMessageIndex (message) {
        return this.messages.findIndex(object => {
            return object.id === message.id;
        })
    },
})
