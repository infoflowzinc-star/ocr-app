<template>
  <div class="form-overlay">
    <div class="form-container">
      <h2>{{ client ? 'クライアント編集' : '新規登録' }}</h2>

      <form @submit.prevent="submit">
        <label>事務所ID</label>
        <input v-model="form.office_id" required />

        <label>名前</label>
        <input v-model="form.name" required />

        <label>業種</label>
        <input v-model="form.industry" />

        <label>税コード</label>
        <input v-model="form.tax_code" />

        <label>コピー元クライアント</label>
        <select v-model="form.copy_source_client_id">
          <option value="">（コピーしない）</option>
          <option v-for="c in availableClients" :key="c.id" :value="c.id">
            {{ c.id }} - {{ c.name }}
          </option>
        </select>

        <div class="actions">
          <button type="submit">保存</button>
          <button type="button" @click="$emit('close')">キャンセル</button>
        </div>
      </form>
    </div>
  </div>
</template>

<script>
import axios from 'axios'

export default {
  props: {
    client: Object,
  },
  data() {
    return {
      form: {
        office_id: '',
        name: '',
        industry: '',
        tax_code: '',
        copy_source_client_id: '',
      },
      availableClients: [],
    }
  },
  watch: {
    client: {
      immediate: true,
      handler(newVal) {
        if (newVal) {
          this.form = { ...newVal }
        }
      },
    },
  },
  mounted() {
    // クライアント一覧を取得（同じ事務所内）
    if (this.form.office_id) {
      axios.get(`/api/clients?office_id=${this.form.office_id}`).then(res => {
        this.availableClients = res.data.filter(c => !this.client || c.id !== this.client.id)
      })
    }
  },
  methods: {
    async submit() {
      try {
        if (this.client) {
          await axios.put(`/api/clients/${this.client.id}`, this.form)
        } else {
          await axios.post('/api/clients', this.form)
        }
        this.$emit('saved')
        this.$emit('close')
      } catch (error) {
        alert('保存に失敗しました')
      }
    },
  },
}
</script>

<style scoped>
.form-overlay {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background: rgba(0,0,0,0.3);
  display: flex;
  justify-content: center;
  align-items: center;
}
.form-container {
  background: white;
  padding: 20px;
  border-radius: 8px;
  width: 400px;
}
label {
  display: block;
  margin-top: 10px;
}
input, select {
  width: 100%;
  padding: 6px;
  margin-top: 4px;
}
.actions {
  margin-top: 20px;
  display: flex;
  justify-content: space-between;
}
</style>
