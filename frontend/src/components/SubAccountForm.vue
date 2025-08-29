<template>
  <div class="form-overlay">
    <div class="form-container">
      <h2>{{ subAccount ? '補助科目編集' : '新規補助科目登録' }}</h2>

      <form @submit.prevent="submit">
        <label>親科目</label>
        <select v-model="form.account_id" required>
          <option value="">（選択してください）</option>
          <option v-for="a in availableAccounts" :key="a.id" :value="a.id">
            {{ a.id }} - {{ a.name }}
          </option>
        </select>

        <label>補助科目コード</label>
        <input v-model="form.code" required />

        <label>補助科目名</label>
        <input v-model="form.name" required />

        <div class="actions">
          <button type="submit">保存</button>
          <button type="button" @click="$emit('close')">キャンセル</button>
        </div>

        <div v-if="subAccount" class="delete-section">
          <button type="button" @click="confirmDelete">削除</button>
        </div>
      </form>
    </div>
  </div>
</template>

<script>
import axios from 'axios'

export default {
  props: {
    subAccount: Object,
  },
  data() {
    return {
      form: {
        account_id: '',
        code: '',
        name: '',
      },
      availableAccounts: [],
    }
  },
  watch: {
    subAccount: {
      immediate: true,
      handler(newVal) {
        if (newVal) {
          this.form = { ...newVal }
        }
      },
    },
  },
  mounted() {
    axios.get('/api/accounts').then(res => {
      this.availableAccounts = res.data
    })
  },
  methods: {
    async submit() {
      try {
        if (this.subAccount) {
          await axios.put(`/api/sub-accounts/${this.subAccount.id}`, this.form)
        } else {
          await axios.post('/api/sub-accounts', this.form)
        }
        this.$emit('saved')
        this.$emit('close')
      } catch (error) {
        alert('保存に失敗しました')
      }
    },
    async confirmDelete() {
      const ok = confirm('この補助科目を削除しますか？仕訳で使用されている場合は削除できません。')
      if (!ok) return

      try {
        await axios.delete(`/api/sub-accounts/${this.subAccount.id}`)
        this.$emit('saved')
        this.$emit('close')
      } catch (error) {
        alert('削除できません。仕訳で使用されている可能性があります。')
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
.delete-section {
  margin-top: 20px;
  text-align: right;
}
</style>
