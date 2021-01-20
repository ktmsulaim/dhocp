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
              <h3>Profile</h3>
            </div>
            <div class="card-body">
              <div class="container">
                <div class="row my-3">
                  <div class="col col-md-4 text-center">
                    <img
                      src="/img/user.png"
                      alt="User icon"
                      class="img-fluid"
                    />
                    <div class="mt-2 mb-4 mb-md-0">
                      <h4>{{ user.data.name }}</h4>
                    </div>
                  </div>
                  <div class="col col-md-8">
                    <div class="table-responsive">
                      <table class="table border">
                        <tr>
                          <th width="100">Batch</th>
                          <td>{{ user.data.batch.name }}</td>
                        </tr>
                        <tr>
                          <th width="100">Enroll no</th>
                          <td>{{ user.data.enroll_no }}</td>
                        </tr>
                        <tr>
                          <th width="100">DOB</th>
                          <td>{{ user.data.dob_formatted }}</td>
                        </tr>
                        <tr>
                          <th width="100">Status</th>
                          <td>
                            <badge v-if="user.data.active == 1" type="success"
                              >Active</badge
                            >
                            <badge v-else type="danger">Suspended</badge>
                          </td>
                        </tr>
                      </table>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import DashboardLayout from "../../layout/users/DashboardLayout";

export default {
  layout: DashboardLayout,
  props: ["user"],
  data() {
    return {};
  },
  created() {
    this.$store.dispatch("assignTitle", "Profile");
  },
};
</script>

<style>
</style>