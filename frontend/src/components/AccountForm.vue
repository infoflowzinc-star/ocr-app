<template>
  <div class="form-overlay">
    <div class="form-container">
      <h2>{{ account ? '科目編集' : '新規科目登録' }}</h2>

      <form @submit.prevent="submit">
        <label>クライアントID</label>
        <input v-model="form.client_id" required />

        <label>科目コード</label>
        <input v-model="form.code" required />

        <label>科目名</label>
        <input v-model="form.name" required />

        <label>種別</label>
        <select v-model="form.type" required>
          <option value="">選択してください</option>
          <option value="資産">資産</option>
          <option value="負債">負債</option>
          <option value="収益">収益</option>
          <option value="費用">費用</option>
          <option value="純資産">純資産</option>
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
    account: Object,
  },
  data() {
    return {
      form: {
        client_id: '',
        code: '',
        name: '',
        type: '',
      },
    }
  },
  watch: {
    account: {
      immediate: true,
      handler(newVal) {
        if (newVal) {
          this.form = { ...newVal }
        }
      },
    },
  },
  methods: {
    async submit() {
      try {
        if (this.account) {
          await axios.put(`/api/accounts/${this.account.id}`, this.form)
        } else {
          await axios.post('/api/accounts', this.form)
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
