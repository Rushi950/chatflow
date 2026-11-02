# ChatFlow - Real-Time Chat Application

A fully functional, real-time chat application built with the **VILT stack** (Vue, Inertia, Laravel, Tailwind). Users can create conversations, send messages, and see updates instantly without refreshing the page using WebSockets.

![chatflow app screenshot]<img width="1920" height="1080" alt="image" src="https://github.com/user-attachments/assets/ecbabae2-58c1-4482-9eb6-050fa6efc79c" />


## 🚀 Tech Stack
*   **Backend:** Laravel 12
*   **Frontend:** Vue 3 + Inertia.js + Tailwind CSS
*   **Real-Time:** Laravel Reverb (WebSockets)
*   **Database:** MySQL
*   **Testing:** PHPUnit (27 passing tests)

## ✨ Key Features
*   **Real-Time Messaging:** Powered by Laravel Reverb, messages appear instantly for all participants in a conversation without a page reload.
*   **Many-to-Many Relationships:** Utilizes a pivot table (`conversation_user`) to allow multiple users to join the same conversation.
*   **Security & Authorization:** 
    *   `ConversationPolicy` ensures only members of a conversation can view it.
    *   Channel authorization in `routes/channels.php` prevents users from listening to WebSockets they don't belong to.
    *   Direct checks ensure users can only edit or delete their *own* messages.
*   **Inline Editing/Deleting:** Users can hover over their own messages to reveal a sleek UI for editing or deleting messages.
*   **Auto-Scrolling:** The chat window automatically scrolls to the newest message.

## 🧠 Architecture Highlights
*   **State Management:** Uses Vue's `ref` and a `watch` on Inertia props to ensure that local UI state stays in sync with backend updates (like when a message is deleted).
*   **WebSocket Deduplication:** Implements a check in the Echo listener to prevent the "double message" bug (where a message appears once from the HTTP response and once from the WebSocket broadcast).
*   **Resource Collections:** Uses Laravel `MessageResource` and `ConversationResource` to format data cleanly before sending it to Vue.

## 🛠️ Installation (Local Development)

1. Clone the repository:
   ```bash
   git clone https://github.com/Rushi950/chatflow.git
   cd chatflow
