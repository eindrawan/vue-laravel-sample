<template>
  <div>
    <div class="container" v-if="loading">loading...</div>

    <div class="container" v-if="!loading">
      <b-card :header="'Welcome, ' + account.name" class="mt-3">
        <b-card-text>
          <div>
            Account: <code>{{ account.id }}</code>
          </div>
          <div>
            Balance:
            <code
              >{{ account.currency === "usd" ? "$" : "€"
              }}{{ account.balance }}</code
            >
          </div>
        </b-card-text>
        <b-button size="sm" variant="success" @click="show = !show"
          >New payment</b-button
        >

        <b-button
          class="float-right"
          variant="danger"
          size="sm"
          nuxt-link
          to="/"
          >Logout</b-button
        >
      </b-card>

      <b-card v-show="errorMessage" class="mt-3 error">
        {{ errorMessage }}
      </b-card>

      <b-card class="mt-3" header="New Payment" v-show="show">
        <b-form @submit="onSubmit">
          <b-form-group id="input-group-1" label="To:" label-for="input-1">
            <b-form-input
              id="input-1"
              size="sm"
              v-model="payment.to"
              type="number"
              required
              placeholder="Destination ID"
            ></b-form-input>
          </b-form-group>

          <b-form-group id="input-group-2" label="Amount:" label-for="input-2">
            <b-input-group prepend="$" size="sm">
              <b-form-input
                id="input-2"
                v-model="payment.amount"
                type="number"
                required
                placeholder="Amount"
              ></b-form-input>
            </b-input-group>
          </b-form-group>

          <b-form-group id="input-group-3" label="Details:" label-for="input-3">
            <b-form-input
              id="input-3"
              size="sm"
              v-model="payment.details"
              required
              placeholder="Payment details"
            ></b-form-input>
          </b-form-group>

          <b-button type="submit" size="sm" variant="primary">Submit</b-button>
        </b-form>
      </b-card>

      <b-card class="mt-3" header="Payment History">
        <b-table striped hover :items="transactions"></b-table>
      </b-card>
    </div>
  </div>
</template>

<script lang="ts">
import axios from "axios";
import Vue, { PropType } from "vue";
import http from "../../plugins/http";
import {
  AccountService,
  TransactionObject,
  AccountObject
} from "../../services/Accounts";

export default Vue.extend({
  name: "AccountPage",
  data() {
    return {
      show: false,
      payment: {} as TransactionObject,
      service: {} as AccountService,
      account: {} as AccountObject,
      errorMessage: "",
      transactions: [] as Array<TransactionObject>,
      loading: false
    };
  },

  async mounted() {
    this.loading = true;
    this.service = new AccountService(Number(this.$route.params.id));
    await this.populate();
    this.loading = false;
  },

  methods: {
    async populate() {
      let [detail, trans] = await Promise.all([
        this.service.getDetail(),
        this.service.getTransactions()
      ]);

      if (!Object.keys(detail).length) {
        window.location.href = "/";
      } else {
        this.account = detail;
        this.transactions = trans;

        var transactions: Array<TransactionObject> = [];
        for (let i = 0; i < this.transactions.length; i++) {
          if (this.transactions && this.transactions[i]) {
            this.transactions[i].amount =
              (this.account.currency === "usd" ? "$" : "€") +
              this.transactions[i].amount;

            if (this.account.id != this.transactions[i].to) {
              this.transactions[i].amount = "-" + this.transactions[i].amount;
            }

            transactions.push(this.transactions![i]);
          }
        }

        this.transactions = transactions;
      }
    },
    async onSubmit(evt: Event) {
      evt.preventDefault();

      let ret = await this.service.transfer(
        this.payment.to,
        Number(this.payment.amount),
        this.payment.details
      );
      this.errorMessage = ret.data.error;

      this.payment = {} as TransactionObject;
      this.show = false;

      // update items
      this.populate();
    }
  }
});
</script>
<style scoped>
.error .card-body {
  color: red;
}
</style>
