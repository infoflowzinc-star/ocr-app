<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue'
import { dashboard } from '@/routes'
import { type BreadcrumbItem } from '@/types'
import { Head, useForm } from '@inertiajs/vue3'
import PlaceholderPattern from '@/components/PlaceholderPattern.vue'

defineProps<{ usages: Array<any>, filters: { yyyymm?: string, office_name?: string } }>()

const breadcrumbs: BreadcrumbItem[] = [
  {
    title: 'Dashboard',
    href: dashboard().url,
  },
]

const form = useForm({
  yyyymm: filters.yyyymm || '',
  office_name: filters.office_name || '',
})

const updateForm = useForm({ billing_status: '' })
</script>

<template>
  <Head title="Dashboard" />

  <AppLayout :breadcrumbs="breadcrumbs">
    <div class="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4">
      <!-- 絞り込みフォーム -->
      <form @submit.prevent="form.get(route('usage.index'))" class="mb-4 flex gap-4">
        <input v-model="form.yyyymm" placeholder="年月 (例: 202508)" class="border px-2 py-1" />
        <input v-model="form.office_name" placeholder="事務所名" class="border px-2 py-1" />
        <button type="submit" class="bg-blue-500 text-white px-4 py-1">絞り込み</button>
      </form>

      <!-- CSV出力リンク -->
      <a :href="'/dashboard/usage/export'" class="text-blue-600 underline mb-4">📄 CSV出力</a>

      <!-- 実データ表示 -->
      <div v-for="u in usages" :key="u.id" class="border p-4 rounded mb-4">
        <h2 class="font-bold">{{ u.office.name }}</h2>
        <p>年月: {{ u.yyyymm }}</p>
        <p>仕訳数: {{ u.entry_count }}</p>

        <form
          @submit.prevent="updateForm.patch(route('usage.update', u.id), { billing_status: u.billing_status })"
          class="mt-2 flex items-center gap-2"
        >
          <select v-model="u.billing_status" class="border px-2 py-1">
            <option value="未請求">未請求</option>
            <option value="請求済">請求済</option>
            <option value="支払済">支払済</option>
          </select>
          <button type="submit" class="bg-green-600 text-white px-3 py-1 rounded">更新</button>
        </form>
      </div>
    </div>
  </AppLayout>
</template>
