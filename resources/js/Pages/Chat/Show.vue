<script setup>
import { onMounted, onUnmounted, ref, nextTick, watch } from 'vue'; 
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, usePage } from '@inertiajs/vue3';
import MessageBubble from '@/Components/Chat/MessageBubble.vue';
import MessageInput from '@/Components/Chat/MessageInput.vue';

const props = defineProps({
    conversation: Object,
    messages: Object 
});

const localMessages = ref(props.messages.data);
const chatContainer = ref(null); 

const scrollToBottom = async () => {
    await nextTick(); 
    if (chatContainer.value) {
        chatContainer.value.scrollTop = chatContainer.value.scrollHeight;
    }
};

watch(() => props.messages.data, (newMessages) => {
    localMessages.value = newMessages;
    scrollToBottom();
}, { deep: true });

onMounted(() => {
    scrollToBottom();
    
    window.Echo.private(`conversation.${props.conversation.id}`)
        .listen('MessageSent', (e) => {
            const messageExists = localMessages.value.some(m => m.id === e.message.id);

            if (!messageExists) {
                localMessages.value.push(e.message);
                scrollToBottom();
            }
        });
});

onUnmounted(() => {
    window.Echo.leave(`conversation.${props.conversation.id}`);
});

const getChatName = (conversation) => {
    if (conversation.is_group) return conversation.name;
    const page = usePage();
    const currentUserId = page.props.auth.user.id;
    const otherUser = conversation.users.find(u => u.id !== currentUserId);
    return otherUser ? otherUser.name : 'Unknown User';
};
</script>

<template>
    <Head :title="getChatName(conversation)" />
    
    <AuthenticatedLayout>
        <template #header>
            <div class="flex justify-between items-center">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ getChatName(conversation) }}</h2>
                <Link :href="route('conversations.index')" class="text-blue-600 hover:underline">Back to Messages</Link>
            </div>
        </template>
        
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 py-6">
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 flex flex-col h-[75vh]">

                <div ref="chatContainer" class="flex-1 overflow-y-auto p-4 space-y-4 bg-gray-50">
 
                    <p v-if="localMessages.length === 0" class="text-center text-gray-500 italic mt-10">
                        No messages yet. Say hello!
                    </p>
                    
                    <MessageBubble
                        v-for="message in localMessages"
                        :key="message.id"
                        :message="message"
                        :is-own-message="message.user_id === $page.props.auth.user.id"
                    />
                </div>
 
                <MessageInput :conversation-id="conversation.id" />
                
            </div>
        </div>
    </AuthenticatedLayout>
</template>