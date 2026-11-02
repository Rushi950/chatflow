<script setup>
import { Link,usePage} from '@inertiajs/vue3';

const props = defineProps({
    conversation: Object
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
    <Link 
        :href="route('conversations.show', conversation.id)" 
        class="block hover:bg-gray-50 transition border-b border-gray-100"
    >
        <div class="p-4 flex justify-between items-center">
            <div class="flex-1 min-w-0">
                <h2 class="font-bold text-gray-800 text-lg truncate">{{ getChatName(conversation) }}</h2>
                <p class="text-sm text-gray-500 truncate">{{ conversation.latest_message?.body || 'No messages yet!' }}</p>
            </div>
            <div class="ml-4 flex-shrink-0">
                <span class="text-xs text-gray-400">{{ conversation.latest_message?.created_at }}</span>
            </div>
        </div>     
    </Link>
</template>