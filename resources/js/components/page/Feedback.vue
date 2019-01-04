<template>
    <div>
        <vk-modal-title>Help make neXus better</vk-modal-title>
            <p>Your feedback lets us build the best possible tools and dashboards.</p>
            <div class="uk-margin">
                <div class="uk-form-label uk-text-muted">Type</div>
                <select class="uk-select" v-model="type">
                    <option value="feedback">Feedback</option>
                    <option value="suggestion">Suggestion</option>
                    <option value="bug">Bug Report</option>
                    <option value="other">Other</option>
                </select>
            </div>
            <div class="uk-margin">
                <div class="uk-form-label uk-text-muted">Message</div>
                <textarea class="uk-textarea" rows="5" placeholder="" v-model="feedback"></textarea>
            </div>
            <p class="uk-text-right">
                <vk-button @click="closeFeedback()" class="uk-margin-small-right">Cancel</vk-button>
                <vk-button @click="sendFeedback()" type="primary">Send Feedback</vk-button>
            </p>
    </div>
</template>

<script>
import { mapActions } from 'vuex';

export default {
    name: 'nexus-feedback',

    data() {
        return {
            feedback: '',
            type: 'feedback'
        }
    },

    computed: {
        payload() {
            let obj = {
                type: this.type,
                url: window.location.href,
                message: this.feedback
            }
            return obj;
        }
    },

    methods: {
        ...mapActions('json', [
            'saveFeedback'
        ]),
        ...mapActions('page', [
            'addMessage'
        ]),
        
        closeFeedback() {
            this.$emit('close');
        },
        sendFeedback() {
            this.saveFeedback(this.payload);
            this.addMessage('Your Feedback Has Been Saved');
            this.feedback = '';
            this.$emit('close');
        }
    },

}
</script>
