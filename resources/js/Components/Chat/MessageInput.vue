<script setup>
import { useForm } from '@inertiajs/vue3';

const props = defineProps({
    conversationId: Number
});

const form = useForm({
    body: ''
});

const submit = () => {
    if (form.body.trim() === '') return; 
    
    form.post(route('messages.store', props.conversationId), {
        preserveScroll: true,
        onSuccess: () => {
            form.reset(); 
        }
    });
};
</script>

<template>
    <div class="bg-white p-4 border-t border-gray-200">
        <form @submit.prevent="submit" class="flex gap-2">
            <input 
                v-model="form.body" 
                type="text" 
                placeholder="Type a message..."
                class="flex-1 border-gray-300 rounded-full px-4 py-2 focus:border-blue-500 focus:ring-blue-500"
            >
            <button 
                type="submit" 
                :disabled="form.processing"
                class="bg-blue-600 text-white px-6 py-2 rounded-full hover:bg-blue-700 disabled:opacity-50 transition"
            >
                Send
            </button>
        </form>
        <p v-if="form.errors.body" class="text-red-500 text-sm mt-1">{{ form.errors.body }}</p>
    </div>
</template>