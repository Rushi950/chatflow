<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { ref } from 'vue'; 
import ConversationItem from '@/Components/Chat/ConversationItem.vue';

defineProps({
    conversations: Object
});

const showForm = ref(false);

const form = useForm({
    name: ''
});

const submit = () => {
    form.post(route('conversations.store'), {
        onSuccess: () => {
            form.reset();
            showForm.value = false; 
        }
    });
};

const getChatName = (conversation) => {
    if (conversation.is_group) return conversation.name;

    const otherUser = conversation.users.find(u => u.id !== $page.props.auth.user.id);
    return otherUser ? otherUser.name : 'Unknown User';
};
</script>

<template>
    <Head title="Messages" />

    <AuthenticatedLayout>
 
        <template #header>
            <div class="flex justify-between items-center">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Messages
                </h2>
                <button 
                    @click="showForm = !showForm" 
                    class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition cursor-pointer"
                >
                    {{ showForm ? 'Cancel' : '+ New Chat' }}
                </button>
            </div>
        </template>

        <div class="py-8 max-w-3xl mx-auto sm:px-6 lg:px-8">

            <div v-if="showForm" class="bg-white p-4 rounded-lg shadow-sm mb-6 border border-gray-100">
                <form @submit.prevent="submit" class="flex gap-2">
                    <input 
                        v-model="form.name" 
                        type="text" 
                        placeholder="e.g. Project Discussion" 
                        class="flex-1 border-gray-300 rounded-lg focus:border-blue-500 focus:ring-blue-500"
                    >
                    <button type="submit" :disabled="form.processing" class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 disabled:opacity-50 cursor-pointer">
                        Create
                    </button>
                </form>
                <p v-if="form.errors.name" class="text-red-500 text-sm mt-1">{{ form.errors.name }}</p>
            </div>

            <div class="bg-white rounded-lg shadow-sm overflow-hidden border border-gray-100">
                <p v-if="conversations.data.length === 0" class="p-6 text-gray-500 text-center">
                    No conversations yet. Start a new chat!
                </p>

                <div v-else>
                    <ConversationItem 
                        v-for="conversation in conversations.data" 
                        :key="conversation.id" 
                        :conversation="conversation" 
                    />
                </div>
            </div>

        </div>
    </AuthenticatedLayout>
</template>