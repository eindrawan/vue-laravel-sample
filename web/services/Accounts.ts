import http from "../plugins/http";

interface TransactionObject {
  id: number;
  to: number;
  amount: string;
  details: string;
}
interface AccountObject {
  id: number;
  currency: string;
  balance: number;
  name: string;
}
class AccountService {
  id: number;
  constructor(id: number) {
    this.id = id;
  }
  async getDetail(): Promise<AccountObject> {
    let ret = await http.$api.get(`/accounts/${this.id}`);
    if (ret.data.success) {
      http.token = ret.data.token;
      return ret.data.data[0];
    } else {
      return {} as AccountObject;
    }
  }
  async getTransactions(): Promise<Array<TransactionObject>> {
    let ret = await http.$api.get(`/accounts/${this.id}/transactions`);
    return ret.data.data;
  }
  transfer(to: number, amount: number, details: string) {
    return http.$api.post(`/accounts/${this.id}/transactions`, {
      to,
      amount,
      details
    });
  }
}
export { AccountService, TransactionObject, AccountObject };
