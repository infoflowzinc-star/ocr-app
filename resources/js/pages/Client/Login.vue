<script setup>
import { useForm } from '@inertiajs/vue3'

const form = useForm({
  email: '',
  password: '',
})
</script>

<template>
  <div class="min-h-screen flex items-center justify-center bg-gray-50">
    <div class="w-full max-w-md bg-white p-6 rounded shadow">
      <h1 class="text-xl font-bold mb-6 text-center">クライアントログイン</h1>

      <form @submit.prevent="form.post('/client/login')" class="space-y-4">
        <div>
          <label for="email" class="block text-sm font-medium text-gray-700">メールアドレス</label>
          <input
            id="email"
            v-model="form.email"
            type="email"
            class="mt-1 block w-full border px-3 py-2 rounded shadow-sm focus:ring focus:border-blue-300"
            required
          />
        </div>

        <div>
          <label for="password" class="block text-sm font-medium text-gray-700">パスワード</label>
          <input
            id="password"
            v-model="form.password"
            type="password"
            class="mt-1 block w-full border px-3 py-2 rounded shadow-sm focus:ring focus:border-blue-300"
            required
          />
        </div>

        <div v-if="form.errors.email" class="text-red-600 text-sm">
          {{ form.errors.email }}
        </div>

        <button
          type="submit"
          class="w-full bg-blue-600 text-white py-2 px-4 rounded hover:bg-blue-700 transition"
        >
          ログイン
        </button>
      </form>
    </div>
  </div>
</template>
