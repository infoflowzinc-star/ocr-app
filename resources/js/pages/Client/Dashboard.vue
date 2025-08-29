<script setup lang="ts">
import { Head } from '@inertiajs/vue3'

defineProps<{ usages: Array<any> }>()
</script>

<template>
  <Head title="領収書ダッシュボード" />

  <div class="max-w-4xl mx-auto mt-10 space-y-6">
    <h1 class="text-2xl font-bold text-center">領収書ダウンロード</h1>

    <div v-if="usages.length === 0" class="text-center text-gray-500">
      支払済みの仕訳データがありません。
    </div>

    <div v-for="u in usages" :key="u.id" class="border rounded p-4 shadow">
      <h2 class="text-lg font-semibold">{{ u.office.name }}</h2>
      <p>年月: {{ u.yyyymm }}</p>
      <p>仕訳数: {{ u.entry_count }}</p>
      <p>請求状態: {{ u.billing_status }}</p>

      <div v-if="u.billing_status === '支払済'" class="mt-2">
        <a
          :href="`/client/receipt/${u.id}`"
          class="text-blue-600 underline"
          target="_blank"
        >
          📄 領収書PDFをダウンロード
        </a>
      </div>
    </div>
  </div>
</template>
