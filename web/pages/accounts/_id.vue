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

<script>
import axios from "axios";
import Vue from "vue";

export default {
  data() {
    return {
      show: false,
      payment: {},

      account: null,
      transactions: null

      //loading: true
    };
  },

  mounted() {
    this.populate();
  },

  computed: {
    loading() {
      return !Boolean(this.account);
    }
  },

  methods: {
    async populate() {
      let [detail, trans] = await Promise.all([
        axios.get(
          `http://localhost:8000/api/accounts/${this.$route.params.id}`
        ),
        axios.get(
          `http://localhost:8000/api/accounts/${this.$route.params.id}/transactions`
        )
      ]);

      if (!detail.data.success) {
        window.location.href = "/";
      } else {
        this.account = detail.data.data[0];
        this["transactions"] = trans.data.data;

        var transactions = [];
        for (let i = 0; i < this.transactions.length; i++) {
          this.transactions[i].amount =
            (this.account.currency === "usd" ? "$" : "€") +
            this.transactions[i].amount;

          if (this.account.id != this.transactions[i].to) {
            this.transactions[i].amount = "-" + this.transactions[i].amount;
          }

          transactions.push(this.transactions[i]);
        }

        this.transactions = transactions;
      }
    },
    async onSubmit(evt) {
      evt.preventDefault();

      await axios.post(
        `http://localhost:8000/api/accounts/${this.$route.params.id}/transactions`,
        this.payment
      );

      this.payment = {};
      this.show = false;

      // update items
      this.populate();
    }
  }
};
</script>
