new Vue({
    el: '#app',

    data: {
        message: '',
        chat: [],
        yodaReplying: false,
    },

    created: function () {
        if (sessionStorage.getItem('yodachat')) {
            try {
                this.chat = JSON.parse(sessionStorage.getItem('yodachat'));
            } catch (e) {
                sessionStorage.removeItem('yodachat');
            }
        }
    },
    methods: {
        send() {
            if (!this.message)
                return;

            let message = {value: this.message, reply: false};
            this.chat.push(message);
            sessionStorage.setItem('yodachat', JSON.stringify(this.chat));

            axios.post('../back/chat.php', {
                message: this.message
            }).then(response => {
                let message = {value: response.data, reply: true};
                this.chat.push(message);
                sessionStorage.setItem('yodachat', JSON.stringify(this.chat));
                this.yodaReplying = false;

                window.scrollTo(0, document.body.scrollHeight);
            }).catch(e => {
                console.log(e);
            });

            this.message = '';
            this.yodaReplying = true;
        }
    }
});