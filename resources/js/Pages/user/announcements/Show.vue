<template>
  <div>
    <base-header
      type="gradient-success"
      class="pb-6 pb-8 pt-5 pt-md-8"
    ></base-header>
    <div class="container-fluid mt--5">
      <div class="row">
        <div class="col">
          <div class="card shadow border-0">
            <div class="card-header">
              <div class="row align-items-center">
                <div class="col">
                  <h3>View message</h3>
                </div>
                <div class="col text-right">
                  <base-button
                    type="primary"
                    @click="$inertia.get($route('user.inbox'))"
                    >Back</base-button
                  >
                </div>
              </div>
            </div>
            <div class="card-body">
              <h4>{{ announcement.title }}</h4>
              <div>
                {{ announcement.body }}
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import DashboardLayout from "../../../layout/users/DashboardLayout";

export default {
  layout: DashboardLayout,
  props: ["announcement"],
  methods: {
    getUnreadMessageCount() {
      const token = this.$page.props.auth_user.data.api_token;

      if (token) {
        axios
          .get(`/api/getUnreadMessageCount?api_token=${token}`)
          .then((resp) => {
            this.$store.dispatch("fetchUnreadCount", resp.data);
          })
          .catch((err) => {
            console.error("Unable to load unread messages!");
          });
      }
    },
  },
  created() {
    this.$store.dispatch("assignTitle", "View message");

    this.getUnreadMessageCount();
  },
};
</script>

<style>
</style>