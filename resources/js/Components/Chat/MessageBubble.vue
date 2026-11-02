<script setup>
import { ref } from 'vue';
import { router } from '@inertiajs/vue3';

const props = defineProps({
    message: Object,
    isOwnMessage: Boolean
});

const isEditing = ref(false);
const editBody = ref(props.message.body);

const submitEdit = () => {
    if (editBody.value.trim() === '') return;
    router.put(route('messages.update', props.message.id), {
        body: editBody.value
    }, {
        onSuccess: () => isEditing.value = false,
        preserveScroll: true
    });
};

const deleteMessage = () => {
    if (confirm('Delete this message?')) {
        router.delete(route('messages.destroy', props.message.id), {
            preserveScroll: true
        });
    }
};
</script>
<template>

    <div 
        class="group flex w-full items-center gap-2"
        :class="isOwnMessage ? 'justify-end' : 'justify-start'"
    >
 
        <div v-if="isOwnMessage && !isEditing" class="hidden group-hover:flex gap-2 text-gray-500">
            <button @click="isEditing = true" class="hover:text-blue-600 text-sm transition">✎</button>
            <button @click="deleteMessage" class="hover:text-red-600 text-sm transition">✕</button>
        </div>

        <div 
            class="max-w-[70%] p-3 shadow-sm"
            :class="isOwnMessage 
                ? 'bg-blue-600 text-white rounded-l-lg rounded-tr-lg' 
                : 'bg-gray-200 text-gray-800 rounded-r-lg rounded-tl-lg'"
        >
            <p v-if="!isOwnMessage" class="text-xs font-bold mb-1 opacity-75">
                {{ message.user?.name || 'Deleted User' }}
            </p>

            <div v-if="isEditing" class="flex gap-2">
                <input v-model="editBody" type="text" class="text-gray-800 w-full rounded px-2 py-1 text-sm">
                <button @click="submitEdit" class="text-green-300 font-bold text-sm">Save</button>
                <button @click="isEditing = false" class="text-red-300 font-bold text-sm">X</button>
            </div>

            <p v-else class="text-sm">{{ message.body }}</p>
            
            <p class="text-[10px] mt-1 text-right opacity-75">
                {{ message.created_at }}
            </p>
        </div>
        
    </div>
</template>