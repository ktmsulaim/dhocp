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
              <h3>Inbox</h3>
            </div>
            <div class="card-body">
              <div v-if="announcements && announcements.length > 0">
                <div class="table-responsive">
                  <table class="table border">
                    <thead>
                      <tr>
                        <th>#</th>
                        <th>Title</th>
                        <th>Date</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr
                        :class="{ 'bg-light': ann.pivot.read_at != null }"
                        style="cursor: pointer"
                        v-for="(ann, i) in announcements"
                        :key="ann.id"
                        @click="
                          $inertia.get(
                            $route('user.inbox.show', { id: ann.id })
                          )
                        "
                      >
                        <td>{{ i + 1 }}</td>
                        <td>{{ ann.title }}</td>
                        <td>
                          {{
                            ann.created_at | moment("DD MMMM YYYY, h:mm:ss a")
                          }}
                        </td>
                      </tr>
                    </tbody>
                  </table>
                </div>
              </div>
              <div v-else>No messages found!</div>
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
  props: ["announcements"],
  created() {
    this.$store.dispatch("assignTitle", "Inbox");

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
};
</script>

<style>
</style>