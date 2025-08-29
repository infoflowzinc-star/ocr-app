<template>
  <div class="account-manager">
    <h1>勘定科目管理</h1>

    <div class="actions">
      <button @click="openForm()">新規登録</button>
    </div>

    <table>
      <thead>
        <tr>
          <th>コード</th>
          <th>科目名</th>
          <th>種別</th>
          <th>補助科目数</th>
          <th>操作</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="account in accounts" :key="account.id">
          <td>{{ account.code }}</td>
          <td>{{ account.name }}</td>
          <td>{{ account.type }}</td>
          <td>{{ account.sub_accounts.length }}</td>
          <td>
            <button @click="editAccount(account)">編集</button>
            <button @click="deleteAccount(account.id)">削除</button>
          </td>
        </tr>
      </tbody>
    </table>

    <AccountForm
      v-if="showForm"
      :account="selectedAccount"
      @close="closeForm"
      @saved="fetchAccounts"
    />
  </div>
</template>

<script>
import axios from 'axios'
import AccountForm from '@/components/AccountForm.vue'

export default {
  components: { AccountForm },
  data() {
    return {
      accounts: [],
      showForm: false,
      selectedAccount: null,
    }
  },
  methods: {
    fetchAccounts() {
      axios.get('/api/accounts').then(res => {
        this.accounts = res.data
      })
    },
    openForm() {
      this.selectedAccount = null
      this.showForm = true
    },
    editAccount(account) {
      this.selectedAccount = account
      this.showForm = true
    },
    closeForm() {
      this.showForm = false
    },
    deleteAccount(id) {
      if (confirm('削除してもよろしいですか？')) {
        axios.delete(`/api/accounts/${id}`).then(() => {
          this.fetchAccounts()
        })
      }
    },
  },
  mounted() {
    this.fetchAccounts()
  },
}
</script>

<style scoped>
.account-manager {
  padding: 20px;
}
.actions {
  margin-bottom: 10px;
}
table {
  width: 100%;
  border-collapse: collapse;
}
th, td {
  padding: 8px;
  border: 1px solid #ccc;
}
</style>
