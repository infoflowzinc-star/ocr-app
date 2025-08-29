<template>
  <div class="form-overlay">
    <div class="form-container">
      <h2>{{ template ? '仕訳テンプレート編集' : '新規テンプレート登録' }}</h2>

      <form @submit.prevent="submit">
        <label>クライアントID</label>
        <input v-model="form.client_id" required />

        <label>タイトル</label>
        <input v-model="form.title" required />

        <label>借方科目</label>
        <select v-model="form.debit_account_id" required>
          <option value="">選択してください</option>
          <option v-for="a in availableAccounts" :key="a.id" :value="a.id">
            {{ a.code }} - {{ a.name }}
          </option>
        </select>

        <label>貸方科目</label>
        <select v-model="form.credit_account_id" required>
          <option value="">選択してください</option>
          <option v-for="a in availableAccounts" :key="a.id" :value="a.id">
            {{ a.code }} - {{ a.name }}
          </option>
        </select>

        <label>金額</label>
        <input type="number" v-model="form.amount" required />

        <label>摘要</label>
        <input v-model="form.description" />

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
    template: Object,
  },
  data() {
    return {
      form: {
        client_id: '',
        title: '',
        debit_account_id: '',
        credit_account_id: '',
        amount: '',
        description: '',
      },
      availableAccounts: [],
    }
  },
  watch: {
    template: {
      immediate: true,
      handler(newVal) {
        if (newVal) {
          this.form = { ...newVal }
        }
      },
    },
  },
  mounted() {
    if (this.form.client_id) {
      axios.get(`/api/accounts?client_id=${this.form.client_id}`).then(res => {
        this.availableAccounts = res.data
      })
    }
  },
  methods: {
    async submit() {
      try {
        if (this.template) {
          await axios.put(`/api/journal-templates/${this.template.id}`, this.form)
        } else {
          await axios.post('/api/journal-templates', this.form)
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
